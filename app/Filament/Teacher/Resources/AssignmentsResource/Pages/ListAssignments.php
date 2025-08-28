<?php

namespace App\Filament\Teacher\Resources\AssignmentsResource\Pages;

use App\Filament\Teacher\Resources\AssignmentsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAssignments extends ListRecords
{
    protected static string $resource = AssignmentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
