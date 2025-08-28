<?php

namespace App\Filament\Resources\AssignmentsResource\Pages;

use App\Filament\Resources\AssignmentsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAssignment extends ViewRecord
{
    protected static string $resource = AssignmentsResource::class;

    public function getHeaderActions(): array  {
        return [
            Actions\Action::make('backToIndex')
                ->label('All assignments')
                ->color('gray')
                ->icon('heroicon-m-arrow-left')
                ->url(AssignmentsResource::getUrl('index')),
        ];
    }

}
