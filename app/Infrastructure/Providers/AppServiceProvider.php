<?php

namespace App\Infrastructure\Providers;

use App\Application\DataSource\CryptoDataSource;
use App\Infrastructure\CoinLoreCryptoDataManager;
use App\Application\CacheSource\CacheSource;
use App\Infrastructure\Cache\WalletCache;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(CryptoDataSource::class, function () {
            return new CoinLoreCryptoDataManager();
        });

        $this->app->bind(CacheSource::class, function () {
            return new WalletCache();
        });

    }
}

