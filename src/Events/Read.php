<?php

namespace Railroad\Resora\Events;

use Railroad\Resora\Entities\Entity;

class Read
{
    /**
     * @var string
     */
    public $class;

    /**
     * @var integer
     */
    public $requestedId;

    /**
     * @var Entity
     */
    public $entity;

    /**
     * @param string $class
     * @param integer $requestedId
     * @param Entity $entity
     */
    public function __construct($class, $requestedId, $entity)
    {
        $this->class = $class;
        $this->requestedId = $requestedId;
        $this->entity = $entity;
    }
}