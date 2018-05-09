<?php

namespace Railroad\Resora\Repositories;

use Railroad\Resora\Collections\BaseCollection;
use Railroad\Resora\Decorators\Decorator;
use Railroad\Resora\Queries\BaseQuery;
use Railroad\Resora\Repositories\Traits\CreateReadUpdateDestroy;

class RepositoryBase
{
    use CreateReadUpdateDestroy;

    /**
     * @var BaseQuery
     */
    protected $query;

    /**
     * @return BaseQuery|$this
     */
    public function query()
    {
        $this->query = $this->newQuery();

        return $this;
    }

    /**
     * @return BaseQuery
     */
    public function getCurrentQuery()
    {
        return $this->query;
    }

    /**
     * @return BaseQuery
     */
    public function continueOrNewQuery()
    {
        return $this->query ?? $this->query();
    }

    /**
     * @return BaseQuery|$this
     */
    protected function newQuery()
    {
        return new BaseQuery($this->connection());
    }

    protected function connection()
    {
        return app('db')->connection(config('resora.default_connection_name'));
    }

    protected function decorate($results)
    {
        return Decorator::decorate($results, 'all');
    }

    public function __call($name, $arguments)
    {
        $results = call_user_func_array([$this->query, $name], $arguments);

        if ($results instanceof BaseQuery) {
            return $this;
        }

        $this->query = null;

        if ($results instanceof \Illuminate\Support\Collection) {
            $results = new BaseCollection($results);
        }

        return $this->decorate($results);
    }
}