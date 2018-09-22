<?php

namespace App\Utilities;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * A search match for the class query function
 *
 * Class SearchMatch
 * @package App\Utilities
 */
class SearchMatch
{
    const MATCH_REGULAR = 0;
    const MATCH_REGEX = 1;

    /**
     * The entity in question
     *
     * @var mixed
     */
    private $entity;

    /**
     * Whether this is indeed a match
     * @var bool
     */
    public $matched = false;

    /**
     * The matched properties
     *
     * @var SearchMatchProperty[]
     */
    private $properties = [];

    /**
     * The matched class
     *
     * @var string
     */
    private $class;

    /**
     * The search query string
     *
     * @var string
     */
    private $query;

    public function __construct($entity, $query, $type)
    {
        $this->entity = $entity;
        $this->query = $query;

        // Initialise dependencies and functions
        $reader = new AnnotationReader();
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $matcher = $this->getMatcher($type);

        // Initialise reflection to get properties
        $reflClass = new \ReflectionClass($entity);
        $this->class = $reflClass->getName();

        // Loop through all the properties
        foreach ($reflClass->getProperties() as $property) {
            // Try to find the annotation that signifies we can be searched
            if ($reader->getPropertyAnnotation($property, 'App\Annotations\Searchable')) {
                // This is a searchable property. Let's search it!
                // Get the value using its getter function
                $value = $propertyAccessor->getValue($entity, $property->getName());

                if ($matcher($value)) {
                    // A match has been found!
                    // Store the property
                    $this->properties[] = new SearchMatchProperty($property->getName(), $value, $type);
                    $this->matched = true;
                }
            }
        }
    }

    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @return SearchMatchProperty[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * Get a function that matches content against a query
     *
     * @param int $type The type of the match (class constant)
     */
    private function getMatcher(int $type): callable
    {
        // Store query for easy access
        $query = $this->query;

        if ($type === self::MATCH_REGEX) {
            return function ($content) use ($query) {
                // Empty strings are evil
                if (empty($content) || empty($query)) {
                    return false;
                }

                // Regex matching
                return (boolean) preg_match('/' . $query . '/', $content);
            };
        } elseif ($type === self::MATCH_REGULAR) {
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
