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

    public function __construct($name, $value, int $matchType)
    {
        $this->name = $name;
        $this->value = $value;
        $this->matchType = $matchType;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
