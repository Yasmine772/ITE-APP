<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Services\MyResourcesListService;
use App\Services\ResourceService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class MyResourceListController extends Controller
{
    use ApiResponseTrait;
    Private MyResourcesListService $myResourcesListService;

    public function __construct(MyResourcesListService $myResourcesListService)
    {
        $this->myResourcesListService = $myResourcesListService;
    }
    public function show(): \Illuminate\Http\JsonResponse
    {
        $myResources = $this->myResourcesListService->getMyResources();
        return $this->successResponse(['Your Resources:'=> $myResources]);
    }
    public function store(int $Id): \Illuminate\Http\JsonResponse
    {
        $resource = $this->myResourcesListService->add($Id);
        return $this->successResponse($resource,$resource['message'],200);
    }


}
