<?php

namespace App\Filament\Resources\TeacherResource\Pages;

use App\Filament\Resources\TeacherResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTeacher extends CreateRecord
{
    protected static string $resource = TeacherResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $user = \App\Models\User::create($data['user']);
        $user->assignRole('teacher');

        $data['user_id'] = $user->id;
        unset($data['user']);

        return $data;
    }
}
