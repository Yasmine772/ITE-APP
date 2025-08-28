<?php

namespace App\Filament\Teacher\Resources\AssignmentsResource\Pages;

use App\Filament\Teacher\Resources\AssignmentsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAssignments extends CreateRecord
{
    protected static string $resource = AssignmentsResource::class;

    protected function getRedirectUrl(): string
    {
        return AssignmentsResource::getUrl('index');
    }
}
