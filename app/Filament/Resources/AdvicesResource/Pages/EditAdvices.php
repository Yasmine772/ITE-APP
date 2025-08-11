<?php

namespace App\Filament\Resources\AdvicesResource\Pages;

use App\Filament\Resources\AdvicesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdvices extends EditRecord
{
    protected static string $resource = AdvicesResource::class;

    protected function getHeaderActions(): array
    {
        return [
           // Actions\DeleteAction::make(),
        ];
    }
}
