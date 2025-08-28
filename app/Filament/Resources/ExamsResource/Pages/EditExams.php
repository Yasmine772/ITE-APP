<?php

namespace App\Filament\Resources\ExamsResource\Pages;

use App\Filament\Resources\ExamsResource;
use Filament\Resources\Pages\EditRecord;

class EditExams extends EditRecord
{
    protected static string $resource = ExamsResource::class;

    protected function getHeaderActions(): array
    {
        return [ ];
    }

    protected function getRedirectUrl(): string
    {
        return ExamsResource::getUrl('index');
    }
}
