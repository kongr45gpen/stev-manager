<?php

namespace App\Utilities;

/**
 * A property of an App\Utilities\SearchMatch
 *
 * Class SearchMatchProperty
 * @package App\Utilities
 */
class SearchMatchProperty
{
    private $name;
    private $value;
    /**
     * The type of the match (App\Utilities\SearchMatch constant)
     *
     * @var int
     */
    private $matchType;

    /**
     * Matched parts
     *
     * An array of [value=>(string)..., matched=>(bool)...] arrays
     *
     * @var array
     */
    private $parts;

    public function __construct($name, $value, int $matchType, string $searchQuery, string $replaceQuery)
    {
        $this->name = $name;
        $this->value = $value;

        $this->matchType = $matchType;

        $this->parts = $this->splitToParts($searchQuery);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }

    /**
     * Matched parts
     *
     * An array of [value=>(string)..., matched=>(bool)...] arrays
     */
    public function getParts(): array
    {
        return $this->parts;
    }

    private function splitToParts($query)
    {
        $parts = [];

        if ($this->matchType === SearchMatch::MATCH_REGEX) {
            $regex = '/(' . $query . ')/';

            $start = 0;

            for ($i = 2; $i <= 20; $i++) {
                $matches = preg_split($regex, $this->value, $i, PREG_SPLIT_DELIM_CAPTURE);

                if (count($matches) < 3 + $start) {
                    break;
                } // No parts remaining

                $parts[] = ['value' => $matches[ $start ], 'matched' => false];
                $parts[] = ['value' => $matches[ $start + 1 ], 'matched' => true];

                $start = count($matches) - 1; // Increase array counter
            }

            // Add the last matched value
            $parts[] = ['value' => array_pop($matches), 'matched' => false];
        } else if ($this->matchType === SearchMatch::MATCH_REGULAR) {
            $matches = explode($query, $this->value);
            foreach($matches as $match) {
                $parts[] = ['value' => $match, 'matched' => false];
                $parts[] = ['value' => $query, 'matched' => true];
            }
            array_pop($parts);
        } else {
            throw new \RuntimeException("Unknown Search Match type {$this->matchType}");
        }

        return $parts;
    }

    private function replace(int $matchType, string $searchQuery, string $replaceQuery)
    {

    }
}
