<?php

namespace App\Filament\Resources\AdvertisementResource\Pages;

use App\Filament\Resources\AdvertisementResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use App\Services\NotificationService;

class CreateAdvertisement extends CreateRecord
{
    protected static string $resource = AdvertisementResource::class;

    protected function afterCreate(): void
    {
        $advertisement = $this->record;

        $users = User::role(['student', 'teacher'])->get();
        $users = $users->filter(fn($user) => $user->id !== auth()->id());

        $advertisement->recipients()->syncWithoutDetaching($users->pluck('id')->toArray());


        $notificationService = app(NotificationService::class);

        $title = $advertisement->title;
        $message = $advertisement->description;
        $advertisementId = $advertisement->id;
        $adminName = auth()->user()->name;

        $students = $users->where('role', 'student');
        if ($students->isNotEmpty()) {
            $notificationService->sendAdvertToUsers(
                $students,
                $title,
                $message,
                $advertisementId,
                $adminName
            );
        }

        $teachers = $users->where('role', 'teacher');
        if ($teachers->isNotEmpty()) {
            $notificationService->sendAdvertToUsers(
                $teachers,
                $title,
                $message,
                $advertisementId,
                $adminName
            );
        }
    }
}
