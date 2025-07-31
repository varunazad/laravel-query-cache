<?php

namespace Varunazad\QueryCache\Listeners;

use Illuminate\Database\Eloquent\Model;
use Varunazad\QueryCache\Facades\QueryCache;

class FlushQueryCache
{
    /**
     * Handle model events and flush relevant cache
     */
    public function handle(Model $model): void
    {
        $this->flushCacheForModel($model);
    }

    /**
     * Flush cache for specific model
     */
    protected function flushCacheForModel(Model $model): void
    {
        if (method_exists(QueryCache::class, 'flushModel')) {
            // Model-specific cache invalidation
            QueryCache::flushModel(
                get_class($model),
                $model->getKey()
            );
        } else {
            // Fallback to full cache flush
            QueryCache::flush();
        }
    }

    /**
     * Register the listeners for the subscriber
     * (Works in Laravel 8/9/10)
     */
    public function subscribe($events): array
    {
        $prefix = version_compare(app()->version(), '9.0', '<') ? 'eloquent.' : '';

        return [
            $prefix.'created: *' => 'handle',
            $prefix.'updated: *' => 'handle',
            $prefix.'deleted: *' => 'handle',
            $prefix.'saved: *' => 'handle',
            $prefix.'restored: *' => 'handle',
        ];
    }
}