# Laravel Query Cache ⚡

![Laravel Query Cache](https://via.placeholder.com/150x50?text=LaravelQueryCache)

> **Blazing-fast query optimization for Laravel**

---

### ✅ Supported Versions

- Laravel: **8.x**, **9.x**, **10.x**
- PHP: **7.4+**, **8.0+**

---

## 🚀 Features

- ⚡ Automatic caching of Eloquent queries  
- 💾 Configurable cache durations  
- 📈 Tag-based cache invalidation
- 🔄 Support for all Laravel cache drivers
- 📊 Minimal configuration required
- 🔍 Works with existing Laravel applications
- 🧮 Query Cachin with pagination

---

## 🚀 Configuration-

   **Publish the configuration file:**
    
    php artisan vendor:publish --provider="VarunAzad\LaravelQueryCache\QueryCacheServiceProvider" --tag="config"
    
    
    **This will create config/query-cache.php with the following options:**
    return [
    'default_ttl' => 3600, // Default cache time in seconds
    'enabled' => env('QUERY_CACHE_ENABLED', true),
    'prefix' => 'query_cache_',
    'store' => env('QUERY_CACHE_STORE', null),
    ];
         
    
  >  **Basic Uses** Caching Queries
      use Varunazad\QueryCache\Facades\QueryCache;
      in you controller 
     / Cache a query for 60 minutes
    $users = User::cache(60)->where('active', true)->get();
    
    // Use default cache time from config
    $posts = Post::cache()->with('comments')->get();

   >  **Pagination query**
      **add this trait  in the model**
        use Varunazad\QueryCache\Traits\Cacheable;
        use Cacheable;
 

        $users = User::with('wallet')->where('status',1)->withCachePaginate(2,60);
        return response()->json($users);





  

---


## 📦 Installation

```bash
composer require varunazad/laravel-query-cache
---



