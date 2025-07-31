<?php

namespace Varunazad\QueryCache;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Foundation\Application;

class QueryCache
{
    protected Cache $cache;
    protected Config $config;
    protected Application $app;
    protected bool $enabled = true;

    public function __construct(Cache $cache, Config $config, Application $app)
    {
        $this->cache = $cache;
        $this->config = $config;
        $this->app = $app;
    }

    public function enable(): void
    {
        $this->enabled = true;
    }

    public function disable(): void
    {
        $this->enabled = false;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function cacheQuery(Builder $query, ?int $ttl = null)
    {
        if (!$this->enabled) {
            return $query->get();
        }

        $ttl = $ttl ?? $this->config->get('querycache.default_ttl', 60);
        $key = $this->generateCacheKey($query);

        return $this->cache->remember($key, $ttl, function () use ($query) {
            return $query->get();
        });
    }

    protected function generateCacheKey(Builder $query): string
    {
        $sql = method_exists($query, 'toRawSql') 
            ? $query->toRawSql() 
            : $query->toSql();

        $bindings = $this->normalizeBindings($query->getBindings());

        return sprintf('query_cache:%s:%s',
            $query->getConnection()->getName(),
            md5($sql . serialize($bindings))
        );
    }

    protected function normalizeBindings(array $bindings): array
    {
        if (version_compare($this->app->version(), '9.0', '<')) {
            return array_map(function ($binding) {
                return $binding instanceof \DateTimeInterface
                    ? $binding->format('Y-m-d H:i:s')
                    : $binding;
            }, $bindings);
        }

        return $bindings;
    }

    public function flush(): bool
    {
        return $this->cache->getStore()->flush();
    }
}