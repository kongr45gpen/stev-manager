<?php

namespace App\Controller;

use App\Entity\Instance;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CalendarController extends AbstractController
{
    /**
     * @Route("/instance/{instance}/calendar", name="calendar")
     */
    public function index(Instance $instance)
    {
        return $this->render('calendar/index.html.twig', [
            'controller_name' => 'CalendarController',
            'events' => $instance->getEvents(),
            'instance' => $instance
        ]);
    }
}
