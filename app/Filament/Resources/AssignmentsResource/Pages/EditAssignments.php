<?php

namespace App\Filament\Resources\AssignmentsResource\Pages;

use App\Filament\Resources\AssignmentsResource;
use Filament\Actions;
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
