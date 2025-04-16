<?php

namespace App\Providers;

use App\Services\BookService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(BookService::class, function ($app) {
            return new BookService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $template = Config::get('pagination.template', 'bootstrap-5');
        switch ($template) {
            case 'bootstrap-4':
            case 'bootstrap-5':
                Paginator::useBootstrap();
                break;
            case 'tailwind':
                Paginator::useTailwind();
                break;
            default:
                Paginator::useBootstrap();
                break;
        }

        Cache::macro('rememberKeyPattern', function ($key, $pattern, $ttl, $callback) {
            $result = Cache::remember($key, $ttl, $callback);

            $patternKeys = Cache::get('cache_keys:' . $pattern, []);
            if (! in_array($key, $patternKeys)) {
                $patternKeys[] = $key;
                Cache::put('cache_keys:' . $pattern, $patternKeys, $ttl);
            }

            return $result;
        });
    }
}
