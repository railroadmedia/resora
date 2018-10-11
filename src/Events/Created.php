<?php

namespace Railroad\Resora\Events;

use Railroad\Resora\Entities\Entity;

class Created
{
    /**
     * @var string
     */
    private $class;

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
     * @param array $attributes
     * @param Entity $entity
     */
    public function __construct($class, $attributes, $entity)
    {
        $this->class = $class;
        $this->attributes = $attributes;
        $this->entity = $entity;
    }
}