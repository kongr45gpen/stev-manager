<?php

namespace App\Controller;

use App\Entity\Instance;
use App\Repository\VolunteerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VolunteersController extends AbstractController
{
    /**
     * @Route("/instance/{instance}/volunteers", name="volunteer_index")
     */
    public function index(Instance $instance, VolunteerRepository $volunteerRepository): Response
    {
        return $this->render('volunteers/index.html.twig', [
            'volunteers' => $volunteerRepository->findByInstance($instance),
            'instance'=> $instance
        ]);
    }
}
