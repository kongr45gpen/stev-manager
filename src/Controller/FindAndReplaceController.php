<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Instance;
use App\Utilities\SearchMatch;
use Carbon\CarbonInterval;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Gedmo\Loggable\Entity\LogEntry;
use Gedmo\Loggable\Entity\Repository\LogEntryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\Annotation\Route;

class FindAndReplaceController extends Controller
{
    /**
     * @Route("/instance/{instance}/find", name="find_and_replace")
     */
    public function index(Instance $instance)
    {
        return $this->render('find_and_replace/index.html.twig', [
            'instance' => $instance
        ]);
    }

    /**
     * @Route("/instance/{instance}/find/search", name="find_and_replace_search")
     */
    public function search(Instance $instance, Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var Event[] $events */
        $events = $instance->getEvents()->toArray();
        $volunteers = $instance->getVolunteers()->toArray();
        $spaces = $this->getDoctrine()->getRepository('App:Space')->findAll();

        // TODO: Eager fetch?
        $submitters = $this->getDoctrine()->getRepository('App:Submitter')->findByInstance($instance);
        $repetitions = $this->getDoctrine()->getRepository('App:Repetition')->findByInstance($instance);

        $entities = array_merge($events, $volunteers, $spaces, $submitters, $repetitions);

        $type = $this->typeStringToConst($request->query->get('options'));

        $searchResults = [];
        if ($request->query->get('query', '') !== '') {
            // Search entities, only if the query is not empty
            foreach ($entities as $entity) {
                $match = new SearchMatch($entity, $request->query->get('query'), $type, $request->query->get('replace', null));

                if ($match->matched) {
                    $searchResults[] = $match;
                }
            }
        }

        $form = $this->createReplaceForm($instance, $request);

        // Sort results by relevance
        usort($searchResults, function(SearchMatch $a, SearchMatch $b) {
            return $b->countMatches() <=> $a->countMatches();
        });

        return $this->render('find_and_replace/search.html.twig', [
            'form' => $form->createView(),
            'matches' => $searchResults,
            'instance' => $instance,
        ]);
    }

    /**
     * @Route("/instance/{instance}/find/replace", name="find_and_replace_replace",
     *     methods={"POST"})
     */
    public function replace(Instance $instance, Request $request)
    {
        $form = $this->createReplaceForm($instance, $request);
        $form->handleRequest($request);

        $post = $request->request;
        $em = $this->getDoctrine()->getManager();
        $logs = $em->getRepository('Gedmo\Loggable\Entity\LogEntry');
        $cache = $this->get('cache.app');

        if ($form->isSubmitted() && $form->isValid()) {
            if ($input = $post->get('replace-entity')) {
                list($entity, $metadatum, $property, $occurrence) = $this->requestToEntity($input);

                // Get the version of the entity
                /** @var LogEntry $oldVersion */
                $oldVersion = $logs->getLogEntriesQuery($entity)
                    ->setMaxResults(1)
                    ->getSingleResult();

                // Calculate the replacement
                $match = new SearchMatch($entity, $post->get('query'), $this->typeStringToConst($post->get('options')), $post->get('replace'));
                if ($property) {
                    if ($occurrence) {
                        dump("I NEED TO REPLACE THE OCCUR! $property -> $occurrence");
                        $match->getProperty($property)->replaceOccurrence($occurrence);
                    }
                    $match->getProperty($property)->performReplacement();
                } else {
                    $match->replaceAll();
                }
                $em->persist($entity);
                $em->flush();

                // Get the changed version of the entity, after the flush
                /** @var LogEntry $newVersion */
                $newVersion = $logs->getLogEntriesQuery($entity)
                    ->setMaxResults(1)
                    ->getSingleResult();

                if ($newVersion->getVersion() !== $oldVersion->getVersion()) {
                    $item = $cache->getItem($this->getCacheKey('undo', $entity, $property))
                        ->expiresAfter(CarbonInterval::hours(1))
                        ->set($oldVersion->getVersion());
                    $cache->save($item);
                    if ($property) {
                        foreach ($match->getProperties() as $currentProperty) {
                            if ($cache->getItem($this->getCacheKey('undo', $entity, $currentProperty->getName()))->get()) {
                                $currentProperty->setUndo(true);
                            }
                        }
                    } else {
                        // We have a new update
                        $item = $cache->getItem($this->getCacheKey('undo', $entity))
                            ->set($oldVersion->getVersion());
                        $cache->save($item);
                        $match->setUndo(true);
                    }
                }

                return $this->render('find_and_replace/search.html.twig', [
                    'form' => $form->createView(),
                    'matches' => [$match],
                    'instance' => $instance,
                ]);
            } elseif ($input = $post->get('undo-entity')) {
                list($entity, $metadatum, $property) = $this->requestToEntity($input);

                $setToVersion = $cache->getItem($this->getCacheKey('undo', $entity, $property));

                if (!$setToVersion->get()) {
                    throw new NotFoundHttpException("No undo action for this.");
                }

                $logs->revert($entity, $setToVersion->get());
                $em->persist($entity);
                $em->flush();
                $setToVersion->set(null); // Do not undo again
                $cache->save($setToVersion);

                $match = new SearchMatch($entity, $post->get('query'), $this->typeStringToConst($post->get('options')), $post->get('replace'));

                return $this->render('find_and_replace/search.html.twig', [
                    'form' => $form->createView(),
                    'matches' => [$match],
                    'instance' => $instance,
                ]);
            }
        }

        throw new NotFoundHttpException("Unknown action.");
    }

    private function createReplaceForm(Instance $instance, Request $request): FormInterface
    {
        /** @var FormFactory $factory */
        $factory = $this->get('form.factory');

        return $factory->createNamedBuilder('', FormType::class, null, [
            'allow_extra_fields' => true
        ])->setAction($this->generateUrl('find_and_replace_replace', ['instance' => $instance->getId()]))
            ->add('query', HiddenType::class, ['data' => $request->query->get('query')])
            ->add('replace', HiddenType::class, ['data' => $request->query->get('replace')])
            ->add('options', HiddenType::class, ['data' => $request->query->get('options')])
            ->getForm();
    }

    /**
     * Convert a search type from the request to the class constant
     * @return int A SearchMatch constant
     */
    private function typeStringToConst(string $type) :int
    {
        return ($type === 'regex') ? SearchMatch::MATCH_REGEX : SearchMatch::MATCH_REGULAR;
    }

    /**
     * Get the cache key for an action
     * @return string
     */
    private function getCacheKey(string $action, $entity, string $property = null)
    {
        $user = $this->getUser();
        $userId = ($user) ? $user->getId() : 0;
        $class = str_replace('\\', '-', get_class($entity));
        $id = $entity->getId();
        $extraProperty = ($property) ? ".$property" : "";

        return "replace.$action.$userId.$class.$id$extraProperty";
    }

    /**
     * Request parameter to [entity, metadatum] array
     * @return [mixed, ClassMetadata, string, int] [entity, metadata, property, occurrence]
     */
    private function requestToEntity(string $entityString)
    {
        $meta = $this->getDoctrine()->getManager()->getMetadataFactory()->getAllMetadata();

        // Make sure the provided class is a doctrine entity of ours
        $parts = explode('.', $entityString);
        // list($type, $id, $property, $occurrence)
        $type = $parts[0];
        $id = $parts[1];
        $property = (isset($parts[2])) ? $parts[2] : null;
        $occurrence = (isset($parts[3])) ? $parts[3] : null;

        $metadatum = array_filter($meta, function(ClassMetadata $datum) use ($type) {
            return $datum->name === "App\\Entity\\$type" && $datum->namespace === "App\Entity";
        });
        if (empty($metadatum)) throw new NotFoundHttpException("Couldn't find entity type {$type}");
        /** @var ClassMetadata $metadatum */
        $metadatum = reset($metadatum);

        // Find the entity itself
        $entity = $this->getDoctrine()->getRepository($metadatum->name)->find($id);
        if (!$entity) throw new NotFoundHttpException("Entity with such ID does not exist.");

        return [$entity, $metadatum, $property, $occurrence];
    }
}
