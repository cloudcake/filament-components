<?php

namespace Cloudcake\Components\Pages\Concerns;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;

trait HasForm
{
    protected static bool $jsRefreshOnSave = false;

    public array $data = [];

    public function fill($values)
    {
        $this->data = $values;
        $this->form->fill($values);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components(fn() => [
                ...$this->schema($schema),

                Form::make([EmbeddedSchema::make('content')])
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make($this->getFooterActions())
                            ->alignment($this->getFormActionsAlignment()),
                    ]),
            ]);
    }

    protected function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label(__('filament-panels::auth/pages/edit-profile.form.actions.save.label'))
            ->submit('save')
            ->keyBindings(['mod+s']);
    }

    protected function getFooterActions(): array
    {
        return [
            $this->getSaveFormAction()
        ];
    }

    protected function getSavedNotification(): Notification
    {
        return Notification::make()
            ->color('success')
            ->icon(static::getNavigationIcon())
            ->title('Saved ' . static::getNavigationLabel());
    }

    protected function getErrorNotification(): Notification
    {
        return Notification::make()
            ->color('danger')
            ->icon(static::getNavigationIcon())
            ->title('Error Saving ' . static::getNavigationLabel());
    }

    protected abstract function schema(Schema $schema): array;

    protected abstract function saved(array $data): bool;

    public function submit(): void
    {
        $this->callHook('beforeValidate');

        $data = $this->form->getState();

        $this->callHook('afterValidate');

        if ($this->saved($data)) {
            $this->getSavedNotification()->send();

            if (static::$jsRefreshOnSave)
                $this->js("window.location.reload()");

            $this->callHook('afterSave');
        } else {
            $this->getErrorNotification()->send();
        }
    }
}