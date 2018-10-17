<?php

namespace Railroad\Resora\Events;

use Railroad\Resora\Entities\Entity;

class Updated
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
     * @var Entity
     */
    public $entity;

    /**
     * @param string $class
     * @param $id
     * @param $attributes
     * @param $entity
     */
    public function __construct($class, $id, $attributes, $entity)
    {
        $this->class = $class;
        $this->id = $id;
        $this->attributes = $attributes;
        $this->entity = $entity;
    }
}