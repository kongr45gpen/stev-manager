<?php

namespace App\Controller;

use App\Entity\Instance;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LandingController extends AbstractController
{
    /**
     * @Route("/", name="landing")
     */
    public function index()
    {
        /** @var Instance[] $instances */
        $instances = $this->getDoctrine()->getRepository('App:Instance')->findAll();

        return $this->render('landing/index.html.twig', [
            'controller_name' => 'LandingController',
            'instances' => $instances
        ]);
    }
}
