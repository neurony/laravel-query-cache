<?php

namespace Neurony\QueryCache\Tests;

use Neurony\QueryCache\Tests\Models\Post;

class IsCacheableQueryBuilderTest extends TestCase
{
    /** @test */
    public function it_can_insert_records()
    {
        Post::query()->insert([
            'name' => 'Post name',
            'slug' => 'post-name',
            'content' => 'Post content',
        ]);

        $this->assertTrue(true);
    }

    /** @test */
    public function it_can_update_records()
    {
        Post::query()->where([
            'name' => 'Post name 1',
        ])->update([
            'name' => 'Post name 111',
        ]);

        $this->assertTrue(true);
    }

    /** @test */
    public function it_can_delete_records()
    {
        Post::query()->delete();

        $this->assertTrue(true);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function resolveApplicationConfiguration($app): void
    {
        parent::resolveApplicationConfiguration($app);

        $app['config']->set('query-cache.all.enabled', true);
        $app['config']->set('query-cache.all.store', 'array');
    }
}
