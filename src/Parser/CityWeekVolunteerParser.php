<?php

namespace App\Parser;

use App\Entity\Event;
use App\Entity\Instance;
use App\Entity\Submitter;
use App\Entity\Volunteer;
use App\Entity\VolunteerAvailability;
use App\Repository\EventRepository;
use App\Repository\VolunteerRepository;
use Carbon\Carbon;
use League\Csv\Reader;
use League\Csv\Statement;

class CityWeekVolunteerParser extends BaseEventParser
{
    /**
     * Parse the CSV data provided by the user
     *
     * @return array
     */
    public function parse(string $path, Instance $instance): array
    {
        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(2); //set the CSV header offset
        $stmt = new Statement();
        $records = $stmt->process($csv);

        /** @var VolunteerRepository $repository */
        $repository = $this->entityManager->getRepository('App:Volunteer');

        $entities = [];

        foreach ($records as $record) {
            if (empty($record['webform_sid']) && empty($record['webform_uid']) && empty($record['epitheto'])) {
                // Empty record, or useless row before header
                continue;
            }

            if ($record['webform_sid'] !== null) {
                $volunteer = $repository->findOneByUniqueId($record['webform_sid']);
            }

            if (!isset($volunteer)) $volunteer = (new Volunteer())->setUniqueId((int) $record['webform_sid']);

            $volunteer
                ->setInstance($instance)
                ->setSurname($record['epitheto'])
                ->setName($record['onoma'])
                ->setFatherName($record['patronymo'])
                ->setEmail($record['email'])
                ->setAge((int) $record['ilikia'])
                ->setPhone($record['til'])
                ->setProperty($record['idiotita'])
                ->setSchool($record['sholi_tmima'])
                ->setLevel($record['epipedo_spoydon']);

            $volunteer->removeAvailabilities();
            if (!empty($record['paraskeyi_05_10_2018'])) {
                $availability = $this->createAvailability(Carbon::createFromDate(2018, 10, 05), $record['paraskeyi_05_10_2018']);
                $entities[] = $availability;
                $volunteer->addAvailability($availability);
            }
            if (!empty($record['savvato_06_10_2018'])) {
                $availability = $this->createAvailability(Carbon::createFromDate(2018, 10, 06), $record['savvato_06_10_2018']);
                $entities[] = $availability;
                $volunteer->addAvailability($availability);
            }
            if (!empty($record['kyriaki_07_10_2018'])) {
                $availability = $this->createAvailability(Carbon::createFromDate(2018, 10, 07), $record['kyriaki_07_10_2018']);
                $entities[] = $availability;
                $volunteer->addAvailability($availability);
            }

            $volunteer
                ->setHealth(
                    ( ($record['ehete_kapoio_iatriko_thema'] !== 'ΟΧΙ') ? '(Ναι) ' : '' ) .
                    $record['parakalo_dieykriniste'])
                ->setShirtSize(explode(' ', $record['noymero_mployzas'])[0])
                ->setSubscription($record['notifications'] === 'ΝΑΙ');

            $volunteer->setOriginalData($record);

            $this->entityManager->persist($volunteer);
            $entities[] = $volunteer;
        }

        $this->entityManager->flush();

        return $entities;
    }

    private function createAvailability(Carbon $date, string $time): VolunteerAvailability {
        list ($start, $end) = explode(' – ', $time);

        $availability = new VolunteerAvailability();
        $availability->setStart($date->copy()->setTimeFromTimeString($start));
        $availability->setEnd($date->copy()->setTimeFromTimeString($end));

        return $availability;
    }
}
