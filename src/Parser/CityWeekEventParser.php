<?php

namespace App\Parser;

use App\Entity\Event;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use League\Csv\Statement;

class CityWeekEventParser extends BaseEventParser
{

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * CityWeekParser constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Parse the CSV data provided by the user
     *
     * @return Event[]
     */
    function parse(string $path): array
    {
        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(2); //set the CSV header offset
        $stmt = new Statement();
        $records = $stmt->process($csv);

        foreach ($records as $record) {
            dump($record);
        }

        return [];
    }
}
