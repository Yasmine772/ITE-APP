<?php

namespace App\Filament\Teacher\Resources\TeacherReferenceResource\Pages;

use App\Filament\Teacher\Resources\TeacherReferenceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTeacherReference extends EditRecord
{
    protected static string $resource = TeacherReferenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
