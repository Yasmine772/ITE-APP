<?php

namespace App\Services;

use App\Models\Resource;
use function Laravel\Prompts\error;

class MyResourcesListService
{
    public function getMyResources(): array
    {
        $myResources = auth()->user()->myResources;
        return [
            $myResources
        ];
    }
    public function add(int $resourceId): array
    {
        try {
            $user = auth()->user();
            $resource = Resource::findOrFail($resourceId);
            if ($user->myResources()->where('resource_id', $resourceId)->exists()) {
                return [
                    'status' => 'error',
                    'message' => 'Resource is already in your resource list.',
                ];
            }
            $user->myResources()->attach($resourceId);

                return [
                    'message' => 'Resource added to your list successfully.',
                ];

        }

        catch (\Exception $ex) {
            return [
                'status' => 'error',
                'message' => $ex->getMessage(),
            ];
        }
    }
    public function remove(int $resourceId): array
    {
        try{
            $user = auth()->user();
            $resource = Resource::findOrFail($resourceId);
            $user->myResources()->detach($resourceId);
            return [
                'status' => 'success',
                'message' => 'Resource removed successfully.',
            ];
        }
        catch (\Exception $ex) {
            return [
                'status' => 'error',
                'message' => $ex->getMessage(),
            ];
        }
    }
}






