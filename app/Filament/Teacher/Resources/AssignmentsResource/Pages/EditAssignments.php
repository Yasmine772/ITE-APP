<?php

namespace App\Filament\Teacher\Resources\AssignmentsResource\Pages;

use App\Filament\Teacher\Resources\AssignmentsResource;
use Filament\Resources\Pages\EditRecord;

class EditAssignments extends EditRecord
{
    protected static string $resource = AssignmentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }

    protected function getRedirectUrl(): string
    {
        return AssignmentsResource::getUrl('index');
    }
}
