<?php

namespace Railroad\Resora\Tests\Functional\Repositories;

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

        $this->assertInstanceOf(RepositoryBase::class, $queryRepository);
    }
}