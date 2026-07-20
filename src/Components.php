<?php

namespace Cloudcake\Components;

use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Contracts\Plugin;
use Filament\Panel;

class Components implements Plugin
{
    /**
     * Make an instance of the plugin.
     *
     * @return static
     */
    public static function make(): static
    {
        return app(static::class);
    }

    /**
     * Unique plugin identifier.
     *
     * @return string
     */
    public function getId(): string
    {
        return 'cloudcake-components';
    }

    /**
     * Plugin registration.
     *
     * @param Panel $panel
     * @return void
     */
    public function register(Panel $panel): void
    {
        $panel->assets([
            Css::make('cloudcake-iconpicker-css', __DIR__ . '/../resources/dist/css/icon-picker.css'),
            Js::make('cloudcake-iconpicker-js', __DIR__ . '/../resources/dist/js/icon-picker.js'),
        ]);
    }

    public function boot(Panel $panel): void
    {
    }
}