<?php

namespace App\Controller;

use App\Entity\Instance;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FindAndReplaceController extends AbstractController
{
    /**
     * @Route("/instance/{instance}/find", name="find_and_replace")
     */
    public function index(Instance $instance)
    {
        return $this->render('find_and_replace/index.html.twig', [
            'controller_name' => 'FindAndReplaceController',
            'instance' => $instance
        ]);
    }
}
