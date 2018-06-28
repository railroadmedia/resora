<?php

namespace Railroad\Resora\Tests\Resources\Models;

use Railroad\Resora\Queries\BaseQuery;
use Railroad\Resora\Repositories\RepositoryBase;

class ResoraRepository extends RepositoryBase
{
    const TABLE = 'resora';

    /**
     * @return BaseQuery|$this
     */
    protected function newQuery()
    {
        return (new BaseQuery($this->connection()))->from(self::TABLE);
    }

    protected function connection()
    {
        return app('db')->connection(config('resora.default_connection_name'));
    }
}
