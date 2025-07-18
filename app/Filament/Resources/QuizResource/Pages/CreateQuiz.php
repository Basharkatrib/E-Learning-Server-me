<?php

namespace App\Filament\Resources\QuizResource\Pages;

use App\Filament\Resources\QuizResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateQuiz extends CreateRecord
{
    protected static string $resource = QuizResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Quiz created')
            ->body('The quiz has been created successfully.');
    }
} 