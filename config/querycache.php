<?php

return [
    'default_ttl' => env('QUERY_CACHE_TTL', 60),
    'enabled' => env('QUERY_CACHE_ENABLED', true),
    'store' => env('QUERY_CACHE_STORE', null),
    'prefix' => env('QUERY_CACHE_PREFIX', 'query_cache'),
    
    'ignored_tables' => [
        'password_resets',
        'jobs',
        'failed_jobs',
        'sessions',
    ],
];