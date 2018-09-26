<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Instance;
use App\Entity\Repetition;
use App\Parser\BaseEventParser;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactory;
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
        /** @var FormFactory $factory */
        $factory = $this->get('form.factory');
        $em = $this->getDoctrine()->getManager();


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

            $entities = $parser->parse($file->getPathname(), $instance);
            $this->addFlash('success', "Entities added successfully");
            return $this->render('import/success.html.twig', [
                'entities' => $entities,
                'instance' => $instance
            ]);
        }

        $actionForm = $factory->createNamedBuilder('action')
            ->add('createRepetitions', SubmitType::class, ['attr'=>['class'=>'btn-primary']])
            ->add('deleteAndCreateRepetitions', SubmitType::class, ['attr'=>['class'=>'btn-danger']])
            ->add('backupOriginalData', SubmitType::class, ['attr'=>['class'=>'btn-success']])
            ->getForm();
        $actionForm->handleRequest($request);

        if ($actionForm->isSubmitted() && $actionForm->isValid()) {
            if ($actionForm->get('createRepetitions')->isClicked()) {
                $results = $this->calculateRepetitions($instance);

                $totalRepetitions = array_reduce($instance->getEvents()->toArray(),function ($c,Event $i){return $c+$i->getRepetitions()->count();}, 0);
                $this->addFlash('success', count($results) . "/$totalRepetitions repetitions added.");
            } elseif ($actionForm->get('deleteAndCreateRepetitions')->isClicked()) {
                $results = $this->calculateRepetitions($instance, true);

                $totalRepetitions = array_reduce($instance->getEvents()->toArray(),function ($c,Event $i){return $c+$i->getRepetitions()->count();}, 0);
                $this->addFlash('success', count($results) . "/$totalRepetitions repetitions forcefully added.");
            } elseif ($actionForm->get('backupOriginalData')->isClicked()) {
                $count = 0;

                foreach ($instance->getEvents() as $event) {
                    if ($event->getOriginalData() === null) {
                        $count++;
                        $event->setOriginalData($event->getData());
                        $em->persist($event);
                    }
                }

                $em->flush();
                $this->addFlash('success', "$count original data packs added.");
            }
        }

        return $this->render('import/index.html.twig', [
            'actionForm' => $actionForm->createView(),
            'form' => $form->createView(),
            'instance' => $instance
        ]);
    }

    private function calculateRepetitions(Instance $instance, bool $force = false) : array {
        $response = [];

        if ($instance->getType() === Instance::CITY_WEEK) {
            $events = $instance->getEvents();
            foreach ($events as $event) {
                if (!$event->getRepetitions()->isEmpty()) {
                    if ($force) $event->removeRepetitions();
                    else continue;
                }

                $data = $event->getDataAsObject();
                $startTime = $data->time->start ?? null;
                $finishTime = $data->time->finish ?? null;
                if ($startTime === null || $finishTime === null || $startTime === "" || $finishTime === "") continue;
                $duration = $data->time->duration ?? 0;
                $repetitions = (int) $data->time->repetition_count ?? 1;
                /** @var Carbon $carbonStartTime */
                $carbonStartTime = Carbon::createFromFormat('H:i', $startTime);
                /** @var Carbon $carbonFinishTime */
                $carbonFinishTime = Carbon::createFromFormat('H:i', $finishTime);
                $carbonDuration = CarbonInterval::minutes($duration);
                $totalDuration = $repetitions * $duration;
                $totalLength = $carbonFinishTime->diffInMinutes($carbonStartTime);

                $interval = ($totalLength - $totalDuration) / (($repetitions - 1) ?: 1);
                $carbonInterval = CarbonInterval::seconds(round($interval * Carbon::SECONDS_PER_MINUTE));

                for ($i = 0; $i < $repetitions; $i++) {
                    $repetitionStartTime = $carbonStartTime->copy()->addMinutes(($duration + $interval) * $i);
                    $repetitionStart = $instance->getStartDate()->copy()->setTimeFrom($repetitionStartTime);
                    $repetitionEnd = $repetitionStart->copy()->addMinutes($duration);

                    $repetition = new Repetition();
                    $repetition->setDate($repetitionStart)
                        ->setDuration($duration)
                        ->setTime(true);

                    $event->addRepetition($repetition);

                    $response[] = $repetition;
                }

                $this->getDoctrine()->getManager()->persist($event);
            }
        } else {
            throw new \InvalidArgumentException("Cannot calculate repetitions for this instance type.");
        }

        $this->getDoctrine()->getManager()->flush();

        return $response;
    }
}
