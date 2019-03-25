<?php

namespace Neurony\QueryCache\Traits;

use Illuminate\Database\Query\Builder;
use Neurony\QueryCache\Contracts\QueryCacheServiceContract;
use Neurony\QueryCache\Database\QueryCacheBuilder;

trait IsCacheable
{
    /**
     * Boot the model.
     *
     * @return void
     */
    public static function bootIsCacheable()
    {
        static::saved(function ($model) {
            $model->clearQueryCache();
        });

        static::deleted(function ($model) {
            $model->clearQueryCache();
        });
    }

    /**
     * @return string
     */
    public function getQueryCacheTag(): string
    {
        return app('cache.query')->getAllQueryCachePrefix() . '.' . (string)$this->getTable();
    }

    /**
     * @return string
     */
    public function getDuplicateQueryCacheTag(): string
    {
        return app('cache.query')->getDuplicateQueryCachePrefix() . '.' . (string)$this->getTable();
    }

    /**
     * Flush the query cache from Redis only for the tag corresponding to the model instance.
     *
     * @return void
     */
    public function clearQueryCache(): void
    {
        app('cache.query')->clearQueryCache($this);
    }

    /**
     * Get a new query builder instance for the connection.
     *
     * @return Builder
     */
    protected function newBaseQueryBuilder(): Builder
    {
        $cacheAllQueriesForever = false;
        $cacheOnlyDuplicateQueriesOnce = false;

        $connection = $this->getConnection();
        $grammar = $connection->getQueryGrammar();

        if (app('cache.query')->canCacheQueries()) {
            if (app('cache.query')->shouldCacheAllQueries()) {
                $cacheAllQueriesForever = true;
            }

            if (app('cache.query')->shouldCacheDuplicateQueries()) {
                $cacheOnlyDuplicateQueriesOnce = true;
            }
        }

        if ($cacheAllQueriesForever === true) {
            return new QueryCacheBuilder(
                $connection, $grammar, $connection->getPostProcessor(),
                $this->getQueryCacheTag(), app('cache.query')->cacheAllQueriesForeverType()
            );
        }

        if ($cacheOnlyDuplicateQueriesOnce === true) {
            return new QueryCacheBuilder(
                $connection, $grammar, $connection->getPostProcessor(),
                $this->getDuplicateQueryCacheTag(), app('cache.query')->cacheOnlyDuplicateQueriesOnceType()
            );
        }

        return parent::newBaseQueryBuilder();
    }
}
