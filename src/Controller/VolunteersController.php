<?php

namespace App\Controller;

use App\Entity\Instance;
use App\Entity\VolunteerAvailability;
use App\Repository\VolunteerRepository;
use Carbon\Carbon;
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

    /**
     * @Route("/instance/{instance}/volunteers/availability", name="volunteer_availability")
     */
    public function availability(Instance $instance): Response
    {
        /** @var VolunteerAvailability[] $availabilities */
        $availabilities = [];

        $volunteers = $instance->getVolunteers();
        foreach ($volunteers as $volunteer) {
            foreach ($volunteer->getAvailabilities() as $availability) {
                $availabilities[$availability->getStart()->shiftTimezone(date_default_timezone_get())->toRfc3339String()] = [];
                if ($availability->getEnd()) $availabilities[$availability->getEnd()->shiftTimezone(date_default_timezone_get())->toRfc3339String()] = [];
            }
        }

        ksort($availabilities);

        $results = new \SplObjectStorage();

        // Sum of availabilities/date
        $sum = 0;
        $count = 0;
        $max = $min = null;

        foreach ($availabilities as $key => &$availability) {
            /** @var Carbon $date */
            $date = Carbon::parse($key);

            $results[$date] = [];

            foreach ($volunteers as $volunteer) {
                foreach ($volunteer->getAvailabilities() as $availability) {
                    if (!$availability->getEnd()) continue;

                    if ($date->greaterThanOrEqualTo($availability->getStart()) && $date->lessThan($availability->getEnd())) {
                        $results[$date] = array_merge($results[$date], [$volunteer]);;
                        break;
                    }
                }
            }

            if ($results[$date] === []) {
                $results->detach($date);
            } else {
                // Statistics
                // TODO: Find correct percentiles
                $found = count($results[$date]);
                $sum += $found;
                $count += 1;
                if ($max === null || $found > $max) $max = $found;
                if ($min === null || $found < $min) $min = $found;
            }
        }

        $average = $sum/((float) ($count ?: 1));

        $form = $this->createFormBuilder()->getForm(); // ???

        return $this->render('volunteers/availability.html.twig', [
            'availabilities' => $results,
            'average' => $average,
            'p25' => $average + 0.25 * ($max-$min),
            'p75' => $average - 0.25 * ($max-$min),
            'instance'=> $instance,
            'form' => $form->createView()
        ]);
    }
}
