<?php

namespace App\Parser;

use App\Entity\Event;
use App\Entity\Instance;
use App\Entity\Submitter;
use App\Repository\EventRepository;
use League\Csv\Reader;
use League\Csv\Statement;

class CityWeekEventParser extends BaseEventParser
{
    /**
     * Parse the CSV data provided by the user
     *
     * @return Event[]
     */
    function parse(string $path, Instance $instance): array
    {
        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(2); //set the CSV header offset
        $stmt = new Statement();
        $records = $stmt->process($csv);

        /** @var EventRepository $repository */
        $repository = $this->entityManager->getRepository('App:Event');

        $events = [];

        foreach ($records as $record) {
            if ($record['webform_sid'] !== null) {
                $event = $repository->findOneByUniqueId($record['webform_sid']);
            }

            if (!isset($event)) $event = (new Event())->setUniqueId((int) $record['webform_sid']);

            $event->setTitle($record['title']);
            $event->setTeam($record['organiser']);
            $event->setShortDescription($record['description']);

            $event->removeSubmitters();
            for ($i = 1; $i <= 3; $i++) {
                if ($record["surname$i"] != '' || $record["name$i"] != '') {
                    $submitter = new Submitter();
                    $submitter->setName($record["name$i"])
                        ->setSurname($record["surname$i"])
                        ->setProperty($record["property$i"])
                        ->setFaculty($record["faculty$i"])
                        ->setSchool($record["school$i"])
                        ->setSector($record["sector$i"])
                        ->setLab($record["lab$i"])
                        ->setPhone($record["phone$i"])
                        ->setPhoneOther($record["mobile$i"])
                        ->setEmail($record["email$i"]);
                    $event->addSubmitter($submitter);
                    $events[] = $submitter;
                }
            }

            $categories = [];
            if ($record["Πείραμα"] === 'X') $categories[] = 'experiment';
            if ($record["Παρατήρηση"] === 'X') $categories[] = 'observation';
            if ($record["Δημιουργικό εργαστήριο"] === 'X') $categories[] = 'lab';
            if ($record["Παρουσίαση"] === 'X') $categories[] = 'presentation';
            if ($record["Παιχνίδι"] === 'X') $categories[] = 'game';
            if ($record["Επίδειξη"] === 'X') $categories[] = 'demonstration';

            $data = [
                'details' => [
                    'equipment' => $record["equipment"],
                    'cost' => $record['cost']
                ],
                'volunteers' => [
                    'cooperator_count' => $record["cooperator_count"],
                    'student_count' => $record["student_count"]
                ],
                'categories' => $categories,
                'time' => [
                    'start' => $record["time_start"],
                    'finish' => $record["time_finish"],
                    'repetition_count' => $record["repetition_count"],
                    'other' => $record["repetition_other"]
                ],
                'audience' => $record["audience"]
            ];

            $event->setData($data);
            $event->setInstance($instance);

            $this->entityManager->persist($event);
            $events[] = $event;
        }

        $this->entityManager->flush();

        return $events;
    }
}
