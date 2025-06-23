<?php

namespace App\Http\Controllers;

use App\Models\Year;
use App\Http\Requests\YearRequest;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class YearController extends Controller
{
    use ApiResponseTrait;

    // ------------------ API METHODS ------------------

    public function index()
    {
        $years = Year::all();

        if ($years->isEmpty()) {
            return $this->errorResponse('No years found', null, 200);
        }

        return $this->successResponse($years, 'Years retrieved successfully');
    }

    public function show(Year $year)
    {
        return $this->successResponse($year, 'Year retrieved successfully');
    }

    public function store(YearRequest $request)
    {
        $validatedData = $request->validated();

        $year = Year::create($validatedData);

        return $this->successResponse($year, 'Year created successfully', 201);
    }

    public function update(YearRequest $request, Year $year)
    {
        $validatedData = $request->validated();

        $year->update($validatedData);

        return $this->successResponse($year, 'Year updated successfully');
    }

    public function destroy(Year $year)
    {
        $year->delete();

        return $this->successResponse(null, 'Year deleted successfully');
    }



    public function indexView()
    {
        $years = Year::all();

        return view('years.index', compact('years'));
    }

    public function create()
    {
        return view('years.create');
    }

    public function storeView(YearRequest $request)
    {
        $validatedData = $request->validated();

        Year::create($validatedData);

        return redirect()->route('years.index')
            ->with('success', 'Year created successfully');
    }

    public function showView(Year $year)
    {
        return view('years.show', compact('year'));
    }

    public function edit(Year $year)
    {
        return view('years.edit', compact('year'));
    }

    public function updateView(YearRequest $request, Year $year)
    {
        $validatedData = $request->validated();

        $year->update($validatedData);

        return redirect()->route('years.index')
            ->with('success', 'Year updated successfully');
    }

    public function destroyView(Year $year)
    {
        $year->delete();

        return redirect()->route('years.index')
            ->with('success', 'Year deleted successfully');
    }
}
