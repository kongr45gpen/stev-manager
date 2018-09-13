<?php

namespace App\Parser;


use App\Entity\Event;

abstract class BaseEventParser
{
    /**
     * Parse the CSV data provided by the user
     *
     * @return Event[]
     */
    abstract function parse(string $path) : array;
}
