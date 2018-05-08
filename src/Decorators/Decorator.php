<?php

namespace Railroad\Resora\Decorators;

use Railroad\Resora\Collections\BaseCollection;

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
        if (!is_array($data) && !($data instanceof BaseCollection)) {
            return $data;
        }

        if (!empty(config('resora.decorators')) || !empty($decoratorClass)) {

            if (empty($decoratorClass)) {
                $decoratorClassNames = config('resora.decorators')[$type] ?? [];
            } else {
                $decoratorClassNames = [$decoratorClass];
            }

            foreach ($decoratorClassNames as $decoratorClassName) {
                /**
                 * @var $decorator DecoratorInterface
                 */
                $decorator = app()->make($decoratorClassName);

                if (isset($data['id'])) {
                    // singular content
                    $data = $decorator->decorate([0 => $data])[0];
                } else {
                    // multiple contents
                    $data = $decorator->decorate($data);
                }
            }
        }

        return $data;
    }
}