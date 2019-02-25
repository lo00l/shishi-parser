<?php

namespace App\Providers;

use App\AssetManager;
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
        AssetManager::init(env('DOMAIN'), env('IMG_PATH'), env('IMG_RELATIVE_PATH'));
    }
}
