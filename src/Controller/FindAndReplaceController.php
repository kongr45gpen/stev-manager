<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Instance;
use App\Utilities\SearchMatch;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function search(Instance $instance, Request $request)
    {
        if ($request->query->get('query','') === '') {
            throw new HttpException(401, "Please do not throw empty search queries!");
        }

        /** @var Event[] $events */
        $events = $instance->getEvents()->toArray();
        $volunteers = $instance->getVolunteers()->toArray();
        $spaces = $this->getDoctrine()->getRepository('App:Space')->findAll();

        // TODO: Use the query builder for this
        $submitters = $repetitions = [];
        foreach ($events as $event) {
            $submitters = array_merge($submitters, $event->getSubmitters()->toArray());
            $repetitions = array_merge($repetitions, $event->getRepetitions()->toArray());
        }

        $entities = array_merge($events, $volunteers, $spaces, $submitters, $repetitions);

        $type = ($request->query->get('options') === 'regex') ? SearchMatch::MATCH_REGEX : SearchMatch::MATCH_REGULAR;

        $searchResults = [];

        foreach ($entities as $entity) {
            $match = new SearchMatch($entity, $request->query->get('query'), $type);

            if ($match->matched) {
                $searchResults[] = $match;
            }
        }

        return $this->render('find_and_replace/search.html.twig', [
            'matches' => $searchResults,
            'instance' => $instance
        ]);
    }
}
