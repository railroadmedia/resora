<?php

namespace Railroad\Resora\Decorators;

use Railroad\Resora\Collections\BaseCollection;
use Railroad\Resora\Decorators\Entity\EntityDecorator;

class Decorator
{
    /**
     * @param $data
     * @param $type
     * @param null $decoratorClass
     * @return mixed
     */
    public static function decorate($data, $type, $decoratorClass = null)
    {
        if (!is_object($data) && !($data instanceof BaseCollection)) {
            return $data;
        }

        if (empty($decoratorClass)) {
            $decoratorClassNames = config('resora.decorators')[$type] ?? [];
        } else {
            $decoratorClassNames = [$decoratorClass];
        }

        // we always want to convert the results to entities
        $decoratorClassNames = array_merge([EntityDecorator::class,], $decoratorClassNames);


        foreach ($decoratorClassNames as $decoratorClassName) {
            /**
             * @var $decorator DecoratorInterface
             */
            $decorator = app()->make($decoratorClassName);

            if (!($data instanceof BaseCollection)) {
                // singular content
                $data = $decorator->decorate(new BaseCollection([0 => $data]))[0];
            } else {
                // multiple contents
                $data = $decorator->decorate($data);
            }
        }

        return $data;
    }
}