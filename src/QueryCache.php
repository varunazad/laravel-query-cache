<?php

namespace Varunazad\QueryCache;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;

class QueryCache
{
    protected Cache $cache;
    protected bool $enabled = true;

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    public function cache($query, ?int $ttl = null, ?callable $callback = null)
    {
        if (!$this->enabled) {
            return $callback ? $callback($query) : $this->execute($query);
        }

        $ttl = $ttl ?? config('querycache.default_ttl', 60);
        $key = $this->generateCacheKey($query);

        return $this->cache->remember($key, $ttl, function () use ($query, $callback) {
            return $callback ? $callback($query) : $this->execute($query);
        });
    }

    protected function execute($query)
    {
        if ($query instanceof Builder || $query instanceof Relation) {
            return $query->get();
        }
        if ($query instanceof QueryBuilder) {
            return new Collection($query->get());
        }
        return $query;
    }

    public function cachePaginate($query, int $perPage = 15, ?int $ttl = null): LengthAwarePaginator
    {
        return $this->cache($query, $ttl, function ($query) use ($perPage) {
            if ($query instanceof Builder || $query instanceof Relation) {
                return $query->paginate($perPage);
            }
            return paginate($query, $perPage); // Helper for query builder
        });
    }

    protected function generateCacheKey($query): string
    {
        if ($query instanceof Builder || $query instanceof Relation) {
            $sql = $query->toSql();
            $bindings = $this->normalizeBindings($query->getBindings());
            $connection = $query->getConnection()->getName();
        } else {
            $sql = $query->toSql();
            $bindings = $this->normalizeBindings($query->getBindings());
            $connection = $query->getConnection()->getName();
        }

        $page = request()->input('page', 1);
        
        return sprintf('query_cache:%s:%s:%s',
            $connection,
            md5($sql . serialize($bindings)),
            $page
        );
    }

    protected function normalizeBindings(array $bindings): array
    {
        return array_map(function ($binding) {
            return $binding instanceof \DateTimeInterface
                ? $binding->format('Y-m-d H:i:s')
                : $binding;
        }, $bindings);
    }

    // ... (keep existing enable/disable/flush methods)
}