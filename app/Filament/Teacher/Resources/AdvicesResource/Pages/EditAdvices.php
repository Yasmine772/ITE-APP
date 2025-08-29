<?php

namespace App\Filament\Teacher\Resources\AdvicesResource\Pages;

use App\Filament\Teacher\Resources\AdvicesResource;
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
    protected function getRedirectUrl(): string
    {
        return AdvicesResource::getUrl('index');
    }
}
