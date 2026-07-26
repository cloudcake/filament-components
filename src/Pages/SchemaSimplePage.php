<?php

namespace Cloudcake\Components\Pages;

use Cloudcake\Components\Pages\Concerns\HasForm;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Pages\Page;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;

/**
 * @property Schema $form
 */
abstract class SchemaSimplePage extends Page implements HasSchemas, HasActions
{
    use InteractsWithSchemas;
    use InteractsWithActions;
    use HasForm;

    protected string $view = 'cloudcake-components::pages.simple-form';

    public function hasLogo(): bool
    {
        return false;
    }
}