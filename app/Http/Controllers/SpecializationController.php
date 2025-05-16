<?php

namespace App\Http\Controllers;

use App\Models\Specialization;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Traits\ApiResponseTrait;

class SpecializationController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $data = Specialization::all();

        if ($data->isEmpty()) {
            return $this->errorResponse('No specializations found', null, 200);
        }

        return $this->successResponse($data, 'Specializations retrieved successfully');
    }

    public function show($id)
    {
        try {
            $data = Specialization::findOrFail($id);
            return $this->successResponse($data, 'Specialization retrieved successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Specialization not found', $e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Unexpected error occurred', $e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255|unique:specializations,name',
            ]);

            $specialization = Specialization::create($validatedData);

            return $this->successResponse($specialization, 'Specialization created successfully', 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Validation failed', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->errorResponse('Unexpected error during creation', $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $specialization = Specialization::findOrFail($id);

            $validated = $request->validate([
<<<<<<< HEAD
                'name' => 'required|string|max:255|unique:specializations,name,'  . $id,
=======
                'name' => 'required|string|max:255|unique:specializations,name,' ,
>>>>>>> a74942e7baa9c99995047ffcc5334ae48c910eff
            ]);

            $specialization->update($validated);

            return $this->successResponse($specialization, 'Specialization updated successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Specialization not found', $e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Unexpected error during update', $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $specialization = Specialization::findOrFail($id);
            $specialization->delete();

            return $this->successResponse(null, 'Specialization deleted successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Specialization not found', $e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Unexpected error during deletion', $e->getMessage(), 500);
        }
    }

    public function AddSpecializationToYear(Request $request, $specializationId)
    {
        try {
            $specialization = Specialization::findOrFail($specializationId);
            $year = Year::findOrFail($request->year_id);

            $specialization->years()->attach($year);

            return $this->successResponse(null, 'Specialization assigned to year successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Specialization or Year not found', $e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Unexpected error during assignment', $e->getMessage(), 500);
        }
    }
}
