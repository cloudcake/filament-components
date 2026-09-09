<?php

declare(strict_types=1);

namespace Cloudcake\Components\Forms\Components;

use Closure;
use Cloudcake\Components\Enums\MermaidTheme;
use Filament\Forms\Components\Textarea;

class MermaidInput extends Textarea
{
    protected string $view = 'cloudcake-components::forms.mermaid';

    protected Closure|MermaidTheme|null $theme = null;
    protected Closure|MermaidTheme|null $darkTheme = null;

    protected bool|Closure $allowPanning = true;
    protected bool|Closure $allowZooming = true;

    public function panning(bool|Closure $condition = true): static
    {
        $this->allowPanning = $condition;

        return $this;
    }

    public function zooming(bool|Closure $condition = true): static
    {
        $this->allowZooming = $condition;

        return $this;
    }

    public function theme(MermaidTheme|Closure|null $theme = null): static
    {
        $this->theme = $theme;

        return $this;
    }

    public function darkTheme(MermaidTheme|Closure|null $theme = null): static
    {
        $this->darkTheme = $theme;

        return $this;
    }

    public function getTheme() : MermaidTheme
    {
        $theme = $this->evaluate($this->theme);

        if (!$theme)
            return MermaidTheme::Default;

        if ($theme instanceof MermaidTheme)
            return $theme;

        $theme = MermaidTheme::tryFrom($theme);

        return $theme ?? MermaidTheme::Default;
    }

    public function getDarkTheme() : MermaidTheme
    {
        $theme = $this->evaluate($this->darkTheme);

        if (!$theme)
            return MermaidTheme::Default;

        if ($theme instanceof MermaidTheme)
            return $theme;

        $theme = MermaidTheme::tryFrom($theme);

        return $theme ?? MermaidTheme::Default;
    }

    public function allowsPanning() : bool
    {
        return $this->evaluate($this->allowPanning);
    }

    public function allowsZooming() : bool
    {
        return $this->evaluate($this->allowZooming);
    }
}