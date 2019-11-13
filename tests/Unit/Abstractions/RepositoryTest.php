<?php

namespace Inz\Repository\Test\Unit\Abstractions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inz\Base\Abstractions\Repository;
use Inz\Repository\Test\TestCase;
use Inz\Repository\Test\Unit\Abstractions\Helpers\Post;

class RepositoryTest extends TestCase
{
    use RefreshDatabase;

    private $repository;

    public function __construct()
    {
        parent::setUp();
        $this->repository = $this->getRepositoryInstance();
    }

    private function getRepositoryInstance()
    {
        return new class extends Repository
        {
            public function model()
            {
                return Post::class;
            }
        };
    }

    /**
     * @test
     * @group abstraction
     */
    public function testing()
    {
        //
    }
}
