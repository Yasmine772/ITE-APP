<?php

namespace App\Filament\Teacher\Resources\ExamsResource\Pages;

use App\Filament\Teacher\Resources\ExamsResource;
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
