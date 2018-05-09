<?php

namespace Railroad\Resora\Decorators\Entity;

use Railroad\Resora\Decorators\DecoratorInterface;
use Railroad\Resora\Entities\Entity;

class EntityDecorator implements DecoratorInterface
{
    public function decorate($results)
    {
        foreach ($results as $resultsIndex => $result) {
            if (!($result instanceof Entity)) {
                $results[$resultsIndex] = new Entity((array) $result);
            }
        }

        return $results;
    }
}