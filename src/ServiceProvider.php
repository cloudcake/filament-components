<?php

namespace Cloudcake\Components;

use Cloudcake\Components\IconSetManager;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;

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

        FilamentAsset::register([
            Css::make('icon-picker', __DIR__ . '/../resources/dist/css/icon-picker.css'),
            Css::make('mermaid', __DIR__ . '/../resources/dist/css/mermaid.css'),
            Js::make('svg-pan-zoom', __DIR__ . '/../resources/dist/js/svg-pan-zoom.js'),
        ], package: 'cloudcake-components');
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