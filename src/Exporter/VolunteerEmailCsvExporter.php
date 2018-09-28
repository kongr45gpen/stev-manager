<?php

namespace App\Exporter;

use App\Entity\Volunteer;
use League\Csv\Writer;

class VolunteerEmailCsvExporter extends BaseExporter
{
    /**
     * @param Volunteer[] $volunteers
     *
     * @throws \League\Csv\CannotInsertRecord
     */
    public function export($volunteers) {
        $csv = Writer::createFromString('');

        $header = ['email', 'stev_id', 'stev_class', 'name', 'instance'];
        $csv->insertOne($header);

        foreach ($volunteers as $volunteer) {
            $csv->insertOne([
                $volunteer->getEmail(),
                $volunteer->getId(),
                get_class($volunteer),
                $volunteer->getFullName(),
                $volunteer->getInstance()->getId()
            ]);
        }

        return $csv->getContent();
    }
}
