<?php

namespace App\Filament\Resources\AdvicesResource\Pages;

use App\Filament\Resources\AdvicesResource;
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
