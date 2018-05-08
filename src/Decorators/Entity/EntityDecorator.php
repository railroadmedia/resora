<?php

namespace Railroad\Resora\Decorators\Entity;

use Railroad\Resora\Decorators\DecoratorInterface;
use Railroad\Resora\Entities\Entity;

class EntityDecorator implements DecoratorInterface
{
    public function decorate($results)
    {
        $entities = [];

        foreach ($results as $resultsIndex => $result) {
            $entities[$resultsIndex] = new Entity($result);
        }

        return $entities;
    }
}