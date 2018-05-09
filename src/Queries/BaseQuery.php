<?php

namespace Railroad\Resora\Queries;

use Illuminate\Database\Query\Builder;

class BaseQuery extends Builder
{
    protected $identifierColumnName = 'id';
}