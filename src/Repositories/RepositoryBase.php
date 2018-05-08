<?php

namespace Railroad\Resora\Repositories;

use Illuminate\Database\Connection;
use Railroad\Resora\Collections\BaseCollection;
use Railroad\Resora\Decorators\Decorator;
use Railroad\Resora\Queries\BaseQuery;

class RepositoryBase
{
    /**
     * @var BaseQuery
     */
    protected $query;

    /**
     * @var string
     */
    protected $connectionName;

    /**
     * @return BaseQuery|$this
     */
    protected function newQuery()
    {
        if (empty($this->connectionName)) {
            $this->connectionName = config('resora.default_connection_name');
        }

        /**
         * @var $realConnection Connection
         */
        $realConnection = app('db')->connection($this->connectionName);
        $realConfig = $realConnection->getConfig();

        $realConfig['name'] = 'resora_connection_mask_' . $realConfig['name'];

        $maskConnection =
            new Connection(
                $realConnection->getPdo(),
                $realConnection->getDatabaseName(),
                $realConnection->getTablePrefix(),
                $realConfig
            );

        if (!empty($realConnection->getSchemaGrammar())) {
            $maskConnection->setSchemaGrammar($realConnection->getSchemaGrammar());
        }

        $maskConnection->setQueryGrammar($realConnection->getQueryGrammar());
        $maskConnection->setEventDispatcher($realConnection->getEventDispatcher());
        $maskConnection->setPostProcessor($realConnection->getPostProcessor());

        return new BaseQuery($maskConnection);
    }

    /**
     * @return BaseQuery|$this
     */
    public function query()
    {
        $this->query = $this->newQuery();

        return $this;
    }

    public function __call($name, $arguments)
    {
        $results = call_user_func_array([$this->query, $name], $arguments);

        if ($results instanceof BaseQuery) {
            return $this;
        }

        if ($results instanceof \Illuminate\Support\Collection) {
            $results = new BaseCollection($results);
        }

        return Decorator::decorate($results, 'all');
    }
}