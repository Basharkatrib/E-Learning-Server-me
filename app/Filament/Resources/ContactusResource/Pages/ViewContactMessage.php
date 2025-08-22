<?php

namespace App\Filament\Resources\ContactusResource\Pages;

use App\Filament\Resources\ContactusResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewContactMessage extends ViewRecord
{
    protected static string $resource = ContactusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('mark_as_read')
                ->label('Mark as Read')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->action(function () {
                    $this->record->update(['is_read' => true]);
                    $this->notify('success', 'Message marked as read');
                })
                ->visible(fn () => !$this->record->is_read),
            Actions\Action::make('mark_as_unread')
                ->label('Mark as Unread')
                ->icon('heroicon-o-x-circle')
                ->color('warning')
                ->action(function () {
                    $this->record->update(['is_read' => false]);
                    $this->notify('success', 'Message marked as unread');
                })
                ->visible(fn () => $this->record->is_read),
            Actions\DeleteAction::make(),
        ];
    }
}
