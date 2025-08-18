<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditCourse extends EditRecord
{
    protected static string $resource = CourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function resolveRecord($key): Model
    {
        $record = parent::resolveRecord($key);
        
        // Check if teacher can edit this course
        $user = auth()->user();
        if ($user && $user->role === 'teacher' && $record->user_id !== $user->id) {
            abort(403, 'You can only edit your own courses.');
        }
        
        return $record;
    }
}
