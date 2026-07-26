<?php

namespace Cloudcake\Components;

use Cloudcake\Components\IconSetManager;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    public function boot(): void
    {
        $this->bootAssets();
        $this->bootLanguage();
        $this->bootRoutes();
        $this->bootViews();

        $this->app->singleton(IconSetManager::class, function () {
            return new IconSetManager;
        });
    }

    private function bootAssets(): void
    {
        $this->publishes([
            __DIR__ . '/../resources/audio' => public_path('audio'),
        ], 'audio');
    }

    private function bootLanguage(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'cloudcake-components');
    }

    private function bootRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }

    private function bootViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'cloudcake-components');
    }
}