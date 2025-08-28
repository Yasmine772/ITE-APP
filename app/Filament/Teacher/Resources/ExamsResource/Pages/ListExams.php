<?php

namespace App\Filament\Teacher\Resources\ExamsResource\Pages;

use App\Filament\Teacher\Resources\ExamsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExams extends ListRecords
{
    protected static string $resource = ExamsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
