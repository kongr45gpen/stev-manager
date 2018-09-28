<?php

namespace App\Controller;

use App\Entity\Instance;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CalendarController extends AbstractController
{
    /**
     * @Route("/instance/{instance}/calendar", name="calendar_events")
     */
    public function events(Instance $instance)
    {
        return $this->render('calendar/events.html.twig', [
            'events' => $instance->getEvents(),
            'instance' => $instance
        ]);
    }

    /**
     * @Route("/instance/{instance}/calendar/volunteers", name="calendar_volunteers")
     */
    public function volunteers(Instance $instance)
    {
        return $this->render('calendar/volunteers.html.twig', [
            'volunteers' => $instance->getVolunteers(),
            'instance' => $instance
        ]);
    }
}
