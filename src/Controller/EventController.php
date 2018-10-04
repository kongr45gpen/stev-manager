<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Instance;
use App\Entity\Repetition;
use App\Entity\Submitter;
use App\Form\EventType;
use App\Repository\EventRepository;
use Carbon\Carbon;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class EventController extends AbstractController
{
    /**
     * @Route("instance/{instance}/events", name="event_index", methods="GET")
     */
    public function index(Instance $instance, EventRepository $eventRepository): Response
    {
        return $this->render('event/index.html.twig', [
            'events' => $eventRepository->findByInstance($instance),
            'instance'=> $instance
        ]);
    }

    /**
     * @Route("instance/{instance}/events/stats", name="event_stats", methods="GET")
     */
    public function stats(Instance $instance, EventRepository $eventRepository): Response
    {
        /** @var Event[] $events */
        $events = $eventRepository->findByInstance($instance);
        $submitters = $this->getDoctrine()->getRepository('App:Submitter')->findByInstance($instance);
        $repetitions = $this->getDoctrine()->getRepository('App:Repetition')->findByInstance($instance);

        // Populate stats arrays
        $kinds = [];
        foreach ($events as $event) {
            foreach (($event->getDataAsObject()->categories ?? []) as $category) {
                if (is_numeric($category) || empty($category)) continue;
                if (!isset($kinds[$category])) {
                    $kinds[$category] = 1;
                } else {
                    $kinds[$category] += 1;
                }
            }
        }
        $totalMinutes = 0;
        foreach ($repetitions as $repetition) {
            $totalMinutes += $repetition->getDuration();
        }
        $schools = array_count_values(array_map(function (Submitter $s) { return $s->getSchool(); }, $submitters));
        $faculties = array_count_values(array_map(function (Submitter $s) { return $s->getSchool(); }, $submitters));
        $humans = 0;
        foreach ($events as $event) {
            $volunteers = $event->getDataAsObject()->volunteers;
            foreach ($volunteers as $count) {
                $intCount = (int) filter_var($count, FILTER_SANITIZE_NUMBER_INT);
                dump($intCount);
                if ($intCount < 0 || $intCount > 1000) {
                    // Sanity check
                    // TODO: Warn
                    continue;
                }
                $humans += $intCount;
            }
        }
        arsort($kinds);
        arsort($schools);
        arsort($faculties);

        $stats = [
            'count' => count($events),
            'categories' => $kinds,
            'combined_hours' => $totalMinutes/60.0,
            'submitters' => [
                'count' => count($submitters),
                'unique_count' => count(array_unique(array_map(function (Submitter $submitter) { return $submitter->getFullName(); }, $submitters))),
                'schools' => $schools,
                'faculties' => $faculties,
            ],
            'volunteers' => $this->getDoctrine()->getRepository('App:Volunteer')->count([]),
            'humans' => $humans
        ];

        return $this->render('event/stats.html.twig', [
            'stats' => $stats,
            'instance'=> $instance
        ]);
    }
}
