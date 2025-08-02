<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCourse extends CreateRecord
{
    protected static string $resource = CourseResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure teacher creates course for themselves
        $user = auth()->user();
        if ($user && $user->role === 'teacher') {
            $data['user_id'] = $user->id;
        }
        
        return $data;
    }
}
