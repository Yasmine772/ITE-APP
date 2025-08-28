<?php

namespace App\Filament\Teacher\Resources\NotificationResource\Pages;

use App\Filament\Teacher\Resources\NotificationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNotification extends EditRecord
{
    protected static string $resource = NotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
