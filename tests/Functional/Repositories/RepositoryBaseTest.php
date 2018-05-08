<?php

namespace Railroad\Resora\Tests\Functional\Repositories;

use Railroad\Resora\Collections\BaseCollection;
use Railroad\Resora\Entities\Entity;
use Railroad\Resora\Queries\BaseQuery;
use Railroad\Resora\Repositories\RepositoryBase;
use Railroad\Resora\Tests\TestCase;

class RepositoryBaseTest extends TestCase
{
    /**
     * @var RepositoryBase
     */
    protected $repositoryBase;

    protected function setUp()
    {
        parent::setUp();

        $this->repositoryBase = app(RepositoryBase::class);
    }

    public function test_query()
    {
        $queryRepository = $this->repositoryBase->query();
        $rawQuery = $this->repositoryBase->getCurrentQuery();

        $this->assertInstanceOf(RepositoryBase::class, $queryRepository);
        $this->assertInstanceOf(BaseQuery::class, $rawQuery);
    }

    public function test_query_building()
    {
        $rawQuery = $this->repositoryBase->query()->where('id', 1)->getCurrentQuery();

        $this->assertEquals(
            [
                [
                    'type' => 'Basic',
                    'column' => 'id',
                    'operator' => '=',
                    'value' => 1,
                    'boolean' => 'and',
                ],
            ],
            $rawQuery->wheres
        );
    }

    public function test_results_pipe()
    {
        $this->databaseManager->table('resora')->insertGetId(['id' => 1]);

        $results = $this->repositoryBase->query()->where('id', 1)->get();

        $this->assertEquals(
            new BaseCollection([['id' => 1]]),
            $results
        );
    }

    public function test_results_get_decorated()
    {
        $this->databaseManager->table('resora')->insertGetId(['id' => 1]);

        $results = $this->repositoryBase->query()->where('id', 1)->get();

        $this->assertEquals(
            new BaseCollection([new Entity(['id' => 1])]),
            $results
        );
    }
}