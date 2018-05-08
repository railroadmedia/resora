<?php

namespace Railroad\Resora\Entities;

use ArrayObject;

class Entity extends ArrayObject
{
    public function fetch($dotNotationString, $default = '')
    {
        return $this->dot()[$dotNotationString] ?? $default;
    }

    public function dot()
    {
        return array_dot($this->getArrayCopy());
    }

    public function replace(array $data)
    {
        $this->exchangeArray($data);
    }
}