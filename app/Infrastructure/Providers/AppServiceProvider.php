<?php

namespace App\Infrastructure\Providers;

use App\Application\UserDataSource\UserDataSource;
use App\DataSource\Database\EloquentUserDataSource;
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
        $this->app->bind(UserDataSource::class, function () {
            return new CoinLoreCryptoDataManager();
        });
//        $this->app->bind(UserDataSource::class, function () {
//            return new EloquentUserDataSource();
//        });
    }
}
//CoinLoadedCrryptoDatasource
//Dominio
//Cache
