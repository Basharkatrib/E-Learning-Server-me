<?php

namespace App\Filament\Resources\BenefitsCourseResource\Pages;

use App\Filament\Resources\BenefitsCourseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBenefitsCourse extends EditRecord
{
    protected static string $resource = BenefitsCourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
} 