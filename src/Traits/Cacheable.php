<?php

namespace Varunazad\QueryCache\Traits;

use Varunazad\QueryCache\Facades\QueryCache;

trait Cacheable
{
    public static function bootCacheable()
    {
        static::created(function ($model) {
            $model->flushQueryCache();
        });

        static::updated(function ($model) {
            $model->flushQueryCache();
        });

        static::deleted(function ($model) {
            $model->flushQueryCache();
        });
    }

    public function scopeWithCache($query, $ttl = null)
    {
        return QueryCache::cacheQuery($query, $ttl);
    }

    public function flushQueryCache()
    {
        QueryCache::flush();
    }
}