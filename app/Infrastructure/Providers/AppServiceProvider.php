<?php

namespace App\Infrastructure\Providers;

use App\Application\CoinLoreCryptoDataSource\CoinLoreCryptoDataSource;;
use App\Infrastructure\CoinLoreCryptoDataManager;
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
        $this->app->bind(CoinLoreCryptoDataSource::class, function () {
            return new CoinLoreCryptoDataManager();
        });

    }
}

