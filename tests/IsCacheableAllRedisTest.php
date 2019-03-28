<?php

namespace Neurony\QueryCache\Tests;

use Illuminate\Support\Facades\DB;
use Neurony\QueryCache\Tests\Models\Post;

class IsCacheableAllRedisTest extends TestCase
{
    /** @test */
    public function it_caches_duplicate_queries()
    {
        DB::enableQueryLog();

        $this->executePostQueries();

        $this->assertEquals(2, count(DB::getQueryLog()));

        DB::flushQueryLog();

        $this->executePostQueries();

        $this->assertEquals(0, count(DB::getQueryLog()));

        DB::disableQueryLog();
    }

    /** @test */
    public function it_removes_cached_queries_when_creating_a_new_record()
    {
        DB::enableQueryLog();

        $this->executePostQueries();

        Post::create([
            'name' => 'New post name',
            'slug' => 'new-post-name',
            'content' => 'New post content',
        ]);

        DB::flushQueryLog();

        $this->executePostQueries();

        DB::disableQueryLog();

        $this->assertEquals(2, count(DB::getQueryLog()));
    }

    /** @test */
    public function it_removes_cached_queries_when_updating_a_new_record()
    {
        DB::enableQueryLog();

        $this->executePostQueries();

        Post::first()->update([
            'name' => 'Updated post name',
        ]);

        DB::flushQueryLog();

        $this->executePostQueries();

        DB::disableQueryLog();

        $this->assertEquals(2, count(DB::getQueryLog()));
    }

    /** @test */
    public function it_removes_related_cached_queries_when_creating_a_new_record()
    {
        DB::enableQueryLog();

        $this->executePostAndCommentQueries();

        Post::create([
            'name' => 'New post name',
            'slug' => 'new-post-name',
            'content' => 'New post content',
        ]);

        DB::flushQueryLog();

        $this->executePostAndCommentQueries();

        DB::disableQueryLog();

        $this->assertEquals(2, count(DB::getQueryLog()));
    }

    /** @test */
    public function it_removes_related_cached_queries_when_updating_a_new_record()
    {
        DB::enableQueryLog();

        $this->executePostAndCommentQueries();

        Post::first()->update([
            'name' => 'Updated post name',
        ]);

        DB::flushQueryLog();

        $this->executePostAndCommentQueries();

        DB::disableQueryLog();

        $this->assertEquals(2, count(DB::getQueryLog()));
    }

    /** @test */
    public function it_can_disable_query_caching()
    {
        app('cache.query')->disableQueryCache();

        DB::enableQueryLog();

        $this->executePostQueries();

        $this->assertEquals(20, count(DB::getQueryLog()));

        $this->executePostQueries();

        $this->assertEquals(40, count(DB::getQueryLog()));

        DB::disableQueryLog();
    }

    /** @test */
    public function it_can_enable_query_caching()
    {
        app('cache.query')->disableQueryCache();

        DB::enableQueryLog();

        $this->executePostQueries();

        $this->assertEquals(20, count(DB::getQueryLog()));

        DB::flushQueryLog();

        app('cache.query')->enableQueryCache();

        $this->executePostQueries();

        $this->assertEquals(2, count(DB::getQueryLog()));

        DB::flushQueryLog();

        $this->executePostQueries();

        $this->assertEquals(0, count(DB::getQueryLog()));

        DB::disableQueryLog();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function resolveApplicationConfiguration($app): void
    {
        parent::resolveApplicationConfiguration($app);

        $app['config']->set('query-cache.all.enabled', true);
        $app['config']->set('query-cache.all.store', 'redis');
    }
}
