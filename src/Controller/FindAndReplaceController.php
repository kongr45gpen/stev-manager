<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Instance;
use App\Utilities\SearchMatch;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

class FindAndReplaceController extends AbstractController
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

        // TODO: Use the query builder for this
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

        return $this->render('find_and_replace/search.html.twig', [
            'form' => $form->createView(),
            'matches' => $searchResults,
            'instance' => $instance
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

        if ($form->isSubmitted() && $form->isValid()) {
            $meta = $em->getMetadataFactory()->getAllMetadata();
            dump($meta);

            if ($input = $post->get('replace-entity')) {
                // Make sure the provided class is a doctrine entity of ours
                list($type, $id) = explode('.', $input);
                $metadatum = array_filter($meta, function(ClassMetadata $datum) use ($type) {
                    return $datum->name === "App\\Entity\\$type" && $datum->namespace === "App\Entity";
                });
                if (empty($metadatum)) throw new NotFoundHttpException("Couldn't find entity type {$type}");
                /** @var ClassMetadata $metadatum */
                $metadatum = reset($metadatum);

                // Find the entity itself
                $entity = $em->getRepository($metadatum->name)->find($id);
                if (!$entity) throw new NotFoundHttpException("Entity with such ID does not exist.");

                // Calculate the replacement
                $match = new SearchMatch($entity, $post->get('query'), $this->typeStringToConst($post->get('options')), $post->get('replace'));
                $match->replaceAll();
                $em->persist($entity);
                $em->flush();

                return $this->render('find_and_replace/search.html.twig', [
                    'form' => $form->createView(),
                    'matches' => [$match],
                    'instance' => $instance
                ]);
            }
        }

        return new Response('<html><head></head><body>hi</body>');
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

    private function typeStringToConst(string $type) :int
    {
        return ($type === 'regex') ? SearchMatch::MATCH_REGEX : SearchMatch::MATCH_REGULAR;
    }
}
