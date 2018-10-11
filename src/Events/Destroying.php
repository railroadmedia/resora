<?php

namespace Railroad\Resora\Events;

class Destroying
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
     * @param string $class
     * @param integer $id
     */
    public function __construct($class, $id)
    {
        $this->class = $class;
        $this->id = $id;
    }
}