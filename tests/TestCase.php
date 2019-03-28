<?php

namespace Neurony\QueryCache\Tests;

use Neurony\QueryCache\ServiceProvider;
use Neurony\QueryCache\Tests\Models\Comment;
use Neurony\QueryCache\Tests\Models\Post;
use Orchestra\Testbench\TestCase as Orchestra;
use Illuminate\Contracts\Foundation\Application;

abstract class TestCase extends Orchestra
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
        $this->makeModels();
    }

    /**
     * Define environment setup.
     *
     * @param Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [ServiceProvider::class];
    }

    /**
     * Set up the database and migrate the necessary tables.
     *
     * @param $app
     * @return void
     */
    protected function setUpDatabase(Application $app): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
    }

    /**
     * @return void
     */
    protected function makeModels(): void
    {
        for ($i = 1; $i <= 3; $i++) {
            $post = Post::create([
                'name' => 'Post name ' . $i,
                'slug' => 'post-name-' . $i,
                'content' => 'Post content' . $i,
            ]);

            for ($j = 1; $j <= 3; $j++) {
                $post->comments()->create([
                    'title' => 'Comment title ' . $i . ' ' . $j,
                    'content' => 'Comment content ' . $i . ' ' . $j,
                ]);
            }
        }
    }

    /**
     * @return void
     */
    protected function executePostQueries(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Post::all();
        }

        for ($i = 1; $i <= 10; $i++) {
            Post::where(1)->get();
        }
    }

    /**
     * @return void
     */
    protected function executePostAndCommentQueries(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Post::all();
        }

        for ($i = 1; $i <= 10; $i++) {
            Comment::all();
        }
    }
}
