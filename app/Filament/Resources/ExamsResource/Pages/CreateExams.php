<?php

namespace App\Filament\Resources\ExamsResource\Pages;

use App\Filament\Resources\ExamsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateExams extends CreateRecord
{
    protected static string $resource = ExamsResource::class;

    protected function getRedirectUrl(): string
    {
        return ExamsResource::getUrl('index');
    }
   
}
