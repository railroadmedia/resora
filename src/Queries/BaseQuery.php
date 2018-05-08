<?php

namespace Railroad\Resora\Queries;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Grammars\Grammar;
use Illuminate\Database\Query\Processors\Processor;

class BaseQuery extends Builder
{
    protected $identifierColumnName = 'id';
    protected $table = 'table';

    public function __construct(ConnectionInterface $connection, Grammar $grammar = null, Processor $processor = null)
    {
        parent::__construct($connection, $grammar, $processor);

        $this->from($this->table);
    }

    /**
     * @param array $columns
     * @return \Illuminate\Support\Collection
     */
    public function get($columns = ['*'])
    {
        return parent::get($columns);
    }

    /**
     * Only cache queries that are getting rows directly based on id without pagination.
     *
     * @return bool
     */
    public function canBeCached()
    {
        foreach ($this->wheres as $where) {
            if ($where['column'] !== $this->identifierColumnName) {
                return false;
            }
        }

        if (isset($this->offset) || isset($this->unionOffset)) {
            return false;
        }

        return true;
    }
}