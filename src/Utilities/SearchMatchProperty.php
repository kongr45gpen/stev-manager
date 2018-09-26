<?php

namespace App\Utilities;

use Symfony\Component\PropertyAccess\PropertyAccess;

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
    private $replacedValue;

    /**
     * Matched parts
     *
     * An array of [value=>(string)..., matched=>(bool)...] arrays
     *
     * @var array
     */
    private $parts;

    /**
     * The corresponding SearchMatch of this property
     *
     * @var SearchMatch
     */
    private $parent;

    /**
     * Whether this property is undoable
     *
     * @var boolean
     */
    private $undo = false;

    public function __construct($name, $value, SearchMatch $parent)
    {
        $this->name = $name;
        $this->value = $value;
        $this->parent = $parent;

        $this->parts = $this->splitToParts($parent->getQuery());
        $this->replaceAll();
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

    /**
     * The value, after the replacement function has ran on it
     *
     * @return mixed
     */
    public function getReplacedValue()
    {
        return $this->replacedValue;
    }

    public function getUndo(): bool
    {
        return $this->undo;
    }

    public function setUndo(bool $undo): void
    {
        $this->undo = $undo;
    }

    private function splitToParts($query)
    {
        $parts = [];
        $matchType = $this->parent->getMatchType();

        if ($matchType === SearchMatch::MATCH_REGEX) {
            $regex = '/(' . $query . ')/u';

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
        } else if ($matchType === SearchMatch::MATCH_REGULAR) {
            $matches = explode($query, $this->value);
            foreach($matches as $match) {
                $parts[] = ['value' => $match, 'matched' => false];
                $parts[] = ['value' => $query, 'matched' => true];
            }
            array_pop($parts);
        } else {
            throw new \RuntimeException("Unknown Search Match type {$matchType}");
        }

        return $parts;
    }

    /**
     * Replace all the occurrences in this property
     *
     * Only stores the value, doesn't do any actual replacing in the entity
     *
     * @param int|null $limit        The maximum number of replaces to run, or null to replace everything
     */
    public function replaceAll(int $limit = null)
    {
        $searchQuery = $this->parent->getQuery();
        $replaceQuery = $this->parent->getReplace();

        if ($this->parent->getMatchType() === SearchMatch::MATCH_REGEX) {
            $this->replacedValue = preg_replace("/$searchQuery/u", $replaceQuery, $this->value, $limit ?? -1);
        } elseif ($this->parent->getMatchType() === SearchMatch::MATCH_REGULAR) {
            $this->replacedValue = str_replace($searchQuery, $replaceQuery, $this->value, $limit);
        } else {
            throw new \RuntimeException("Unknown Search Match type {$this->parent->getMatchType()}");
        }
    }

    /**
     * Replace only the nth occurrence in this property's value
     *
     * Only stores the value, doesn't do any actual replacing in the entity
     *
     * @param int $n The number of the part (starting from 0, including non-matched parts)
     */
    public function replaceOccurrence(int $n)
    {
        $searchQuery = $this->parent->getQuery();
        $replaceQuery = $this->parent->getReplace();

        if ($this->parent->getMatchType() === SearchMatch::MATCH_REGEX) {
            // Note: This most likely won't work with regex references
            $this->parts[$n]['value'] = preg_replace("/$searchQuery/u", $replaceQuery, $this->parts[$n]['value']);
        } elseif ($this->parent->getMatchType() === SearchMatch::MATCH_REGULAR) {
            $this->parts[$n]['value'] = str_replace($searchQuery, $replaceQuery, $this->parts[$n]['value']);
        }

        dump("New value: " . $this->parts[$n]['value']);

        // Join all the parts together again
        $this->replacedValue = join('', array_column($this->parts, 'value'));
    }

    /*
     * Store the replaced value in the entity
     */
    public function performReplacement()
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $entity = $this->parent->getEntity();
        $propertyAccessor->setValue($entity, $this->name, $this->replacedValue);

        // Show the new information here
        $this->value = $this->replacedValue;
        $this->parts = $this->splitToParts($this->parent->getQuery());
        $this->replaceAll();
    }

    /**
     * Get a function that matches content against a query
     *
     * @param int $type The type of the match (App\Utilities\SearchMatch constant)
     * @param string $query The search query string
     */
    public static function getMatcher(int $type, string $query): callable
    {
        if ($type === SearchMatch::MATCH_REGEX) {
            return function ($content) use ($query) {
                // Empty strings are evil
                if (empty($content) || empty($query)) {
                    return false;
                }

                // Regex matching
                return (boolean) preg_match('/' . $query . '/u', $content);
            };
        } elseif ($type === SearchMatch::MATCH_REGULAR) {
            return function ($content) use ($query) {
                // Empty strings are evil
                if (empty($content) || empty($query)) {
                    return false;
                }

                // Linear search
                return strpos($content, $query) !== false;
            };
        } else {
            throw new \RuntimeException("Unknown search matching type $type");
        }
    }
}
