<?php

namespace App\Filament\Teacher\Resources\ExamsResource\Pages;

use App\Filament\Teacher\Resources\ExamsResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewExams extends ViewRecord
{
    protected static string $resource = ExamsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('backToIndex')
                ->label('All Exams')
                ->color('gray')
                ->icon('heroicon-m-arrow-left')
                ->url(ExamsResource::getUrl('index')),
        ];
    }


}
