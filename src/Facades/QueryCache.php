<?php

namespace Varunazad\QueryCache\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed cacheQuery(\Illuminate\Database\Eloquent\Builder $query, int|null $ttl = null)
 * @method static void flush()
 * @method static void enable()
 * @method static void disable()
 * @method static bool isEnabled()
 * @see \Varunazad\QueryCache\QueryCache
 */
class QueryCache extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'querycache';
    }
}