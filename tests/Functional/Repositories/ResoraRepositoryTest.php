<?php

namespace Railroad\Resora\Tests\Functional\Repositories;

use Railroad\Resora\Collections\BaseCollection;
use Railroad\Resora\Entities\Entity;
use Railroad\Resora\Queries\BaseQuery;
use Railroad\Resora\Repositories\RepositoryBase;
use Railroad\Resora\Tests\TestCase;
use Railroad\Resora\Tests\Resources\Models\ResoraRepository;

class ResoraRepositoryTest extends TestCase
{
    /**
     * @var RepositoryBase
     */
    protected $resoraRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->bind(ResoraRepository::class);

        $this->resoraRepository = $this->app->make(ResoraRepository::class);
    }

    public function test_query()
    {
        $queryRepository = $this->resoraRepository->query();
        $rawQuery = $this->resoraRepository->getCurrentQuery();

        $this->assertInstanceOf(RepositoryBase::class, $queryRepository);
        $this->assertInstanceOf(BaseQuery::class, $rawQuery);
    }

    public function test_query_building()
    {
        $rawQuery = $this->resoraRepository->query()->where('id', 1)->getCurrentQuery();

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

    public function test_subsequent_reads()
    {
        $entityOneData = ['id' => rand(0, 32767)];
        $entityTwoData = ['id' => rand(0, 32767)];

        $this->databaseManager->table(ResoraRepository::TABLE)->insertGetId($entityOneData);
        $this->databaseManager->table(ResoraRepository::TABLE)->insertGetId($entityTwoData);

        $results = $this->resoraRepository->read($entityOneData['id']);

        $this->assertEquals(
            new Entity($entityOneData),
            $results
        );

        $results = $this->resoraRepository->read($entityTwoData['id']);

        $this->assertEquals(
            new Entity($entityTwoData),
            $results
        );
    }

    public function test_read_update_read()
    {
        $entityData = ['id' => rand(0, 32767)];

        $this->databaseManager->table(ResoraRepository::TABLE)->insertGetId($entityData);

        $results = $this->resoraRepository->read($entityData['id']);

        $this->assertEquals(
            new Entity($entityData),
            $results
        );

        $entityNewData = ['id' => rand(0, 32767)];

        $results = $this->resoraRepository->update($entityData['id'], $entityNewData);

        $this->assertDatabaseHas(
            ResoraRepository::TABLE,
            $entityNewData
        );

        $results = $this->resoraRepository->read($entityNewData['id']);

        $this->assertEquals(
            new Entity($entityNewData),
            $results
        );
    }

    public function test_results_pipe_single()
    {
        $this->databaseManager->table(ResoraRepository::TABLE)->insertGetId(['id' => 1]);

        $results = $this->resoraRepository->read(1);

        $this->assertEquals(
            new Entity(['id' => 1]),
            $results
        );
    }

    public function test_results_pipe_multiple()
    {
        $this->databaseManager->table(ResoraRepository::TABLE)->insertGetId(['id' => 1]);
        $this->databaseManager->table(ResoraRepository::TABLE)->insertGetId(['id' => 2]);

        $results = $this->resoraRepository->query()->whereIn('id', [1, 2])->get();

        $this->assertEquals(
            new BaseCollection([new Entity(['id' => 1]), new Entity(['id' => 2])]),
            $results
        );
    }
}