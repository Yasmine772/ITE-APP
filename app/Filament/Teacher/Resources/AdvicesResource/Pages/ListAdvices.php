<?php

namespace App\Filament\Teacher\Resources\AdvicesResource\Pages;

use App\Filament\Teacher\Resources\AdvicesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdvices extends ListRecords
{
    protected static string $resource = AdvicesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
