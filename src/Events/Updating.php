<?php

namespace Railroad\Resora\Events;

class Updating
{
    /**
     * @var string
     */
    public $class;

    /**
     * @var integer
     */
    public $id;

    /**
     * @var array
     */
    public $attributes;

    /**
     * @param string $class
     * @param integer $id
     * @param array $attributes
     */
    public function __construct($class, $id, $attributes)
    {
        $this->class = $class;
        $this->id = $id;
        $this->attributes = $attributes;
    }
}