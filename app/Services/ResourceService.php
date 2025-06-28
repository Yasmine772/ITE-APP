<?php

namespace App\Services;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Imagick;

class ResourceService
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function store(array $data): array
    {
        try {
            $teacher = Auth::user()->teacher;
            if ($data['resourceable_type'] === 'course') {
                $resourceable = $teacher->courses()->where('name', $data['resourceable_name'])->firstOrFail();
            } elseif ($data['resourceable_type'] === 'subject') {
                $resourceable = $teacher->subjects()->where('name', $data['resourceable_name'])->firstOrFail();
            } else {
                throw new \InvalidArgumentException('Invalid resourceable type');
            }

            $realPath = $data['file']->store('resources', 'public');
            $pdfPath = storage_path('app/public/' . $realPath);

            $coverDir = storage_path('app/public/resources/covers');
            if (!file_exists($coverDir)) {
                mkdir($coverDir, 0755, true);
            }

            $baseName = pathinfo($realPath, PATHINFO_FILENAME);
            $coverImagePath = 'resources/covers/' . $baseName . '_cover.jpg';
            $coverFullPath = storage_path('app/public/' . $coverImagePath);


            $imagick = new Imagick();
            $imagick->setResolution(150, 150);
            $imagick->readImage($pdfPath . '[0]');
            $imagick->setImageFormat('jpeg');
            $imagick->writeImage($coverFullPath);
            $imagick->clear();
            $imagick->destroy();

            $resource = Resource::create([
                'title' => $data['title'],
                'file' => $realPath,
                'teacher_id' => $teacher->id,
                'resourceable_type' => $data['resourceable_type'],
                'resourceable_id' => $resourceable->id,
                'cover_image' => $coverImagePath,
            ]);

            $title = 'New reference has been added';
            $message = $resource['title'];
            $resourceId = $resourceable->id;
            $students = User::role('student')->get();
            $this->notificationService->sendResourceToUsers($students, $title, $message, $resourceId);

            return [
                'data' => $resource,
            ];
        } catch (\Exception $ex) {
            return [
                'status' => 'error',
                'message' => $ex->getMessage(),
                'code' => 500,
            ];
        }
    }

    public function update(array $data, int $id): array
    {
        try {
            $teacher = Auth::user()->teacher;
            $resource = Resource::query()->findOrFail($id);

            if ($resource->teacher_id !== $teacher->id) {
                return [
                    'status' => 'error',
                    'message' => 'Unauthorized',
                    'code' => 403,
                ];
            }

            if (isset($data['file'])) {
                if ($resource->file && \Storage::disk('public')->exists($resource->file)) {
                    \Storage::disk('public')->delete($resource->file);
                }

                if ($resource->cover_image && \Storage::disk('public')->exists($resource->cover_image)) {
                    \Storage::disk('public')->delete($resource->cover_image);
                }

                $realPath = $data['file']->store('resources', 'public');
                $pdfPath = storage_path('app/public/' . $realPath);

                $coverDir = storage_path('app/public/resources/covers');
                if (!file_exists($coverDir)) {
                    mkdir($coverDir, 0755, true);
                }

                $baseName = pathinfo($realPath, PATHINFO_FILENAME);
                $coverImagePath = 'resources/covers/' . $baseName . '_cover.jpg';
                $coverFullPath = storage_path('app/public/' . $coverImagePath);


                $imagick = new Imagick();
                $imagick->setResolution(150, 150);
                $imagick->readImage($pdfPath . '[0]');
                $imagick->setImageFormat('jpeg');
                $imagick->writeImage($coverFullPath);
                $imagick->clear();
                $imagick->destroy();

                $data['file'] = $realPath;
                $data['cover_image'] = $coverImagePath;
            }

            $resource->update($data);

            return [
                'status' => 'success',
                'message' => 'Resource updated successfully',
                'data' => $resource,
            ];
        } catch (\Exception $ex) {
            return [
                'status' => 'error',
                'message' => $ex->getMessage(),
                'code' => 500,
            ];
        }
    }

    public function destroy(int $id): array
    {
        $teacher = auth()->user()->teacher;
        $resource = Resource::query()->findOrFail($id);

        if ($resource->teacher_id !== $teacher->id) {
            return [
                'status' => 'error',
                'message' => 'Unauthorized',
                'code' => 403,
            ];
        }

        $resource->delete();

        return [
            'status' => 'success',
        ];
    }

    public function destroyAll(): array
    {
        $teacher = auth()->user()->teacher;
        $resources = $teacher->resources;

        foreach ($resources as $resource) {
            $resource->delete();
        }

        return [
            'status' => 'success',
        ];
    }
}
