<?php

namespace App\Utilities;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
     * The matched entity class
     *
     * @var string
     */
    private $class;

    /**
     * The type of the match (App\Utilities\SearchMatch constant)
     *
     * @var int
     */
    private $matchType;

    /**
     * The search query string
     *
     * @var string
     */
    private $query;

    /**
     * The replacement string
     *
     * @var string
     */
    private $replace;

    /**
     * Whether this match contains an undo entry
     *
     * @var bool
     */
    private $undo = false;

    public function __construct($entity, $query, $type, $replace)
    {
        $this->entity = $entity;
        $this->query = $query;
        $this->replace = $replace;
        $this->matchType = $type;

        // Initialise dependencies and functions
        $reader = new AnnotationReader();
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $matcher = SearchMatchProperty::getMatcher($type, $query);

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
                    $this->properties[] = new SearchMatchProperty($property->getName(), $value, $this);
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

    public function getProperty($name)
    {
        foreach ($this->properties as $property) {
            if ($property->getName() === $name) {
                return $property;
            }
        }

        throw new NotFoundHttpException("Unknown property $name");
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function getReplace(): string
    {
        return $this->replace;
    }

    public function getUndo(): bool
    {
        return $this->undo;
    }

    public function setUndo(bool $undo): void
    {
        $this->undo = $undo;
    }

    /**
     * @return int
     */
    public function getMatchType(): int
    {
        return $this->matchType;
    }

    /**
     * Perform all the replacing changes in entities
     */
    public function replaceAll()
    {
        foreach ($this->properties as $property) {
            $property->performReplacement();
        }
    }

    /**
     * Find if entity will be the same, after a replace has been done
     */
    public function willBeTheSame() :bool
    {
        foreach ($this->properties as $property) {
            if ($property->getValue() !== $property->getReplacedValue()) {
                return false;
            }
        }

        return true;
    }
}
