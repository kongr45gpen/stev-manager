<?php

namespace App\Controller;

use App\Entity\Instance;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class InstanceController extends AbstractController
{
    /**
     * @Route("/instance/{instance}", name="instance")
     */
    public function show(Instance $instance)
    {
        return $this->render('instance/show.html.twig', [
            'controller_name' => 'InstanceController',
            'instance' => $instance
        ]);
    }
}
