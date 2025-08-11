<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Events\RoleUpdated;
use Illuminate\Database\Eloquent\Model;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $oldRole = $record->getOriginal('role');

        $record->update($data);

        if ($oldRole !== $data['role']) {
            $eventData = [
                'message' => "You have been updated to the role {$data['role']}",
                'user_name' => $record->first_name . ' ' . $record->last_name,
                'old_role' => $oldRole,
                'new_role' => $data['role']
            ];
            event(new RoleUpdated($record->id, $eventData));
        }

        return $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
