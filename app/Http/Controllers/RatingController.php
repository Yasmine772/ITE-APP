<?php

namespace App\Http\Controllers;

use App\Http\Requests\RatingRequest;
use App\Services\RatingService;

class RatingController extends Controller
{
    protected $service;

    public function __construct(RatingService $service)
    {
        $this->service = $service;

        $this->middleware('auth:sanctum'); // Sanctum middleware
    }

    public function store(RatingRequest $request)
    {
        $userId = auth()->id();

        $rating = $this->service->rateContent(
            $userId,
            $request->course_content_id,
            $request->rating
        );

        return response()->json([
            'message' => 'Rating saved successfully.',
            'data' => $rating
        ]);
    }
}
