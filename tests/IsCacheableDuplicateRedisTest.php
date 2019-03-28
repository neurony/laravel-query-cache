<?php

namespace Neurony\QueryCache\Tests;

use Illuminate\Support\Facades\DB;

class IsCacheableDuplicateRedisTest extends TestCase
{
    /** @test */
    public function it_caches_only_duplicate_queries()
    {
        DB::enableQueryLog();

        $this->executePostQueries();

        DB::disableQueryLog();

        $this->assertEquals(2, count(DB::getQueryLog()));
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function resolveApplicationConfiguration($app): void
    {
        parent::resolveApplicationConfiguration($app);

        $app['config']->set('query-cache.duplicate.enabled', true);
        $app['config']->set('query-cache.duplicate.store', 'redis');
    }
}
