<?php

namespace Railroad\Resora\Decorators;


interface DecoratorInterface
{
    /**
     * @param $data
     * @return mixed
     */
    public function decorate($data);
}