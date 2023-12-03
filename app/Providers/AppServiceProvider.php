<?php

declare(strict_types=1);

namespace App\Providers;

use App\AlphaVantage\Client\Client;
use Illuminate\Foundation\Application;
use Illuminate\Http\Client\Factory;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerAlphaVantageClient();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * This function will register the Alpha Vantage Client as a singleton.
     * So that any subsequent calls to the Client will return the same instance.
     */
    private function registerAlphaVantageClient(): void
    {
        $apiKey = config('stock_market.alpha_vantage.api_key');
        $apiUrl = config('stock_market.alpha_vantage.api_url');

        assert(is_string($apiKey) && $apiKey !== '', 'API key must not be empty');
        assert(is_string($apiUrl) && $apiUrl !== '', 'API URL must not be empty');

        $this->app->singleton(
            Client::class,
            function(Application $app) use ($apiKey, $apiUrl) {
                return new Client(
                    $app->make(Factory::class),
                    $apiKey,
                    $apiUrl
                );
            }
        );
    }
}
