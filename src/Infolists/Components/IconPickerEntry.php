<?php

declare(strict_types=1);

namespace Cloudcake\Components\Infolists\Components;

use Closure;
use Filament\Infolists\Components\Entry;
use Cloudcake\Components\Concerns\HasIconAnimation;
use Cloudcake\Components\Concerns\HasIconColor;
use Cloudcake\Components\Concerns\HasIconSize;

class IconPickerEntry extends Entry
{
    use HasIconAnimation;
    use HasIconColor;
    use HasIconSize;

    protected string $view = 'cloudcake-components::infolists.components.icon-entry';

    protected bool|Closure $showIconName = true;

    protected string|Closure|null $icon = null;

    /**
     * Set a fixed icon (useful when not using a database column).
     */
    public function icon(string|Closure|null $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->evaluate($this->icon);
    }

    /**
     * Get the state - returns the fixed icon if set, otherwise the database value.
     */
    public function getState(): mixed
    {
        $icon = $this->getIcon();

        if ($icon !== null) {
            return $icon;
        }

        return parent::getState();
    }

    public function showIconName(bool|Closure $show = true): static
    {
        $this->showIconName = $show;

        return $this;
    }

    public function shouldShowIconName(): bool
    {
        return (bool) $this->evaluate($this->showIconName);
    }
}
