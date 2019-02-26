<?php

namespace App\Providers;

use App\AssetManager;
use App\IAssetManager;
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
        $this->app->bind(IAssetManager::class, function ($app) {
            return new AssetManager(env('DOMAIN'), env('IMG_PATH'), env('IMG_RELATIVE_PATH'));
        });
    }
}
