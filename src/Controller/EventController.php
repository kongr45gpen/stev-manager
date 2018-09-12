<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Instance;
use App\Form\EventType;
use App\Repository\EventRepository;
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
}
