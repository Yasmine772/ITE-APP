<?php
namespace App\Http\Controllers;

use App\Models\Specialization;
use App\Models\Year;
use Illuminate\Http\Request;
use App\Http\Requests\SpecializationRequest;
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

    public function show(Specialization $specialization)
    {
        return $this->successResponse($specialization, 'Specialization retrieved successfully');
    }

    public function store(SpecializationRequest $request)
    {
        $specialization = Specialization::create($request->validated());
        return $this->successResponse($specialization, 'Specialization created successfully', 201);
    }

    public function update(SpecializationRequest $request, Specialization $specialization)
    {
        $specialization->update($request->validated());
        return $this->successResponse($specialization, 'Specialization updated successfully');
    }

    public function destroy(Specialization $specialization)
    {
        $specialization->delete();
        return $this->successResponse(null, 'Specialization deleted successfully');
    }

    public function addSpecializationToYear(Request $request, Specialization $specialization)
    {
        $request->validate(['year_id' => 'required|exists:years,id']);
        $year = Year::findOrFail($request->year_id);
        $specialization->years()->syncWithoutDetaching($year->id);
        return $this->successResponse(null, 'Specialization assigned to year successfully');
    }


    public function bladeIndex()
    {
        $specializations = Specialization::all();
        return view('specializations.index', compact('specializations'));
    }

    public function bladeCreate()
    {
        return view('specializations.create');
    }

    public function bladeStore(SpecializationRequest $request)
    {
        Specialization::create($request->validated());
        return redirect()->route('specializations.index')->with('success', 'Specialization created successfully');
    }

    public function bladeEdit(Specialization $specialization)
    {
        return view('specializations.edit', compact('specialization'));
    }

    public function bladeUpdate(SpecializationRequest $request, Specialization $specialization)
    {
        $specialization->update($request->validated());
        return redirect()->route('specializations.index')->with('success', 'Specialization updated successfully');
    }

    public function bladeDestroy(Specialization $specialization)
    {
        $specialization->delete();
        return redirect()->route('specializations.index')->with('success', 'Specialization deleted successfully');
    }
}
