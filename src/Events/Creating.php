<?php

namespace Railroad\Resora\Events;

class Creating
{
    /**
     * @var string
     */
    public $class;

    /**
     * @var array
     */
    public $attributes;

    /**
     * @param string $class
     * @param array $attributes
     */
    public function __construct($class, $attributes)
    {
        $this->class = $class;
        $this->attributes = $attributes;
    }
}