<?php

namespace Varunazad\QueryCache\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Varunazad\QueryCache\Facades\QueryCache;

trait Cacheable
{
    public static function bootCacheable()
    {
        static::created(function ($model) {
            $model->flushQueryCache();
        });
        // ... other events
    }

    public function scopeWithCache(Builder $query, $ttl = null)
    {
        return QueryCache::cache($query, $ttl);
    }

    public function scopeWithCachePaginate(Builder $query, $perPage = 15, $ttl = null)
    {
        return QueryCache::cachePaginate($query, $perPage, $ttl);
    }

    protected function generateCacheKey($query): string
    {
        return QueryCache::generateCacheKey($query);
    }

    public function flushQueryCache()
    {
        QueryCache::flush();
    }
}