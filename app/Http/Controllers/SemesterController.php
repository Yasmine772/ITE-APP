<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Traits\ApiResponseTrait;

class SemesterController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $data = Semester::all();

        if ($data->isEmpty()) {
            return $this->errorResponse('No semesters found', null, 200);
        }

        return $this->successResponse($data, 'Semesters retrieved successfully');
    }

    public function show($id)
    {
        try {
            $data = Semester::findOrFail($id);
            return $this->successResponse($data, 'Semester retrieved successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Semester not found', $e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Unexpected error occurred', $e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255|unique:semesters,name',
            ]);

            $semester = Semester::create($validatedData);

            return $this->successResponse($semester, 'Semester created successfully', 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Validation failed', $e->errors(), 422);

        } catch (\Exception $e) {
            return $this->errorResponse('Unexpected error occurred during creation', $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $semester = Semester::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:semesters,name,' . $id,
            ]);

            $semester->update($validated);

            return $this->successResponse($semester, 'Semester updated successfully');

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Semester not found', $e->getMessage(), 404);

        } catch (\Exception $e) {
            return $this->errorResponse('Unexpected error occurred during update', $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $semester = Semester::findOrFail($id);
            $semester->delete();

            return $this->successResponse(null, 'Semester deleted successfully');

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Semester not found', $e->getMessage(), 404);

        } catch (\Exception $e) {
            return $this->errorResponse('Unexpected error occurred during deletion', $e->getMessage(), 500);
        }
    }
}
