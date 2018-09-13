<?php

namespace App\Parser;


use App\Entity\Event;
use App\Entity\Instance;
use Doctrine\ORM\EntityManagerInterface;

abstract class BaseEventParser
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * CityWeekParser constructor.
     *
     * @param EntityManagerInterface $entityManager
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
    abstract function parse(string $path, Instance $instance) : array;
}
