<?php

declare(strict_types=1);

namespace Cloudcake\Components\Infolists\Components;

use Cloudcake\Components\Forms\Components\MermaidInput;

class MermaidEntry extends MermaidInput
{
    protected string $view = 'cloudcake-components::infolists.mermaid';

    public static function make(?string $name = null): static
    {
        return parent::make($name)
            ->hiddenLabel();
    }
}