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
        $this->registerComponents($panel);
    }

    public function boot(Panel $panel): void
    {
    }

    private function registerComponents(Panel $panel): static
    {
        $panel->assets([
            Css::make('cloudcake-components-css', __DIR__ . '/../resources/dist/css/components.css'),
        ]);

        $panel->assets([
            Js::make('cloudcake-iconpicker', __DIR__ . '/../resources/dist/js/icon-picker.js'),
        ]);

        return $this;
    }
}