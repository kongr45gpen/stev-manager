<?php

namespace App\Controller;

use App\Entity\Instance;
use App\Parser\BaseEventParser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ImportController extends Controller
{
    /**
     * @Route("/instance/{instance}/import", name="import")
     * @throws \Exception
     */
    public function index(Instance $instance, Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('file', FileType::class)
            ->add('import', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        /** @var $parser BaseEventParser */

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();

            if ($instance->getType() === 3) {
                $parser = $this->get('App\Parser\CityWeekEventParser');
            } else {
                throw new \Exception("Import for this instance type undefined");
            }

            $parser->parse($file->getPathname(), $instance);
            $this->addFlash('success', "Entities added successfully");
        }

        return $this->render('import/index.html.twig', [
            'form' => $form->createView(),
            'instance' => $instance
        ]);
    }
}
