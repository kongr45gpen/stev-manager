<?php

namespace App\Controller;

use App\Entity\Instance;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ExportController extends AbstractController
{
    /**
     * @Route("/instance/{instance}/export", name="export")
     */
    public function index(Instance $instance)
    {
        return $this->render('export/index.html.twig', [
            'instance' => $instance
        ]);
    }
}
