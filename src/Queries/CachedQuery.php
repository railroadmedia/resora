<?php

namespace Railroad\Resora\Queries;

use Illuminate\Cache\CacheManager;
use Illuminate\Cache\Repository;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Grammars\Grammar;
use Illuminate\Database\Query\Processors\Processor;

class CachedQuery extends BaseQuery
{
    /**
     * @var Repository
     */
    protected $cache;

    public function __construct(
        ConnectionInterface $connection,
        Grammar $grammar = null,
        Processor $processor = null
    ) {
        parent::__construct($connection, $grammar, $processor);

        $this->cache = app(CacheManager::class)->driver(config('resora.default_cache_driver'));
    }

    /**
     * Only cache queries that are getting rows directly based on id without pagination.
     *
     * @return bool
     */
    public function shouldBeCached()
    {
        foreach ($this->wheres as $where) {
            if ($where['column'] ?? '' !== $this->identifierColumnName) {
                return false;
            }
        }

        if (isset($this->offset) || isset($this->unionOffset)) {
            return false;
        }

        return true;
    }


    public function get($columns = ['*'])
    {
        if (!$this->shouldBeCached()) {
            return parent::get($columns);
        }

        $key = config('resora.cache_key_prefix') . $this->toSql();

        $results = $this->cache->remember(
            $key,
            config('resora.cache_minutes'),
            function () use ($columns) {
                return parent::get($columns);
            }
        );

        return $results;
    }
}