<?php

namespace App\Http\Controllers;

use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Traits\ApiResponseTrait;

class YearController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $data = Year::all();

        if ($data->isEmpty()) {
            return $this->errorResponse('No years found', null, 200);
        }

        return $this->successResponse($data, 'Years retrieved successfully');
    }

    public function show($id)
    {
        try {
            $data = Year::findOrFail($id);
            return $this->successResponse($data, 'Year retrieved successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Year not found', $e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Unexpected error occurred', $e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255|unique:years,name',
            ]);

            $year = Year::create($validatedData);

            return $this->successResponse($year, 'Year created successfully', 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Validation failed', $e->errors(), 422);

        } catch (\Exception $e) {
            return $this->errorResponse('Unexpected error occurred during creation', $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $year = Year::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:years,name,' . $id,
            ]);

            $year->update($validated);

            return $this->successResponse($year, 'Year updated successfully');

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Year not found', $e->getMessage(), 404);

        } catch (\Exception $e) {
            return $this->errorResponse('Unexpected error occurred during update', $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $year = Year::findOrFail($id);
            $year->delete();

            return $this->successResponse(null, 'Year deleted successfully');

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Year not found', $e->getMessage(), 404);

        } catch (\Exception $e) {
            return $this->errorResponse('Unexpected error occurred during deletion', $e->getMessage(), 500);
        }
    }
}
