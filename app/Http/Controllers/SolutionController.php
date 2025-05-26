<?php

namespace App\Http\Controllers;

use App\Models\Solution;
use App\Response\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class SolutionController extends Controller
{
    public function addSolution(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'solutionFile' => 'required|file|mimes:pdf|max:3072',
                'assignment_id' => 'required|string',
            ]);
            if ($validator->fails()) {
                return Response::Error($validator->errors(), 400);
            }
            if (!$request->hasFile('solutionFile')) {
                return Response::Error('file not found!',404);
            }
            $NameOfFile = $request->file('solutionFile')->getClientOriginalName();
            $pathOfFile = $request->file('solutionFile')->storeAs('folderOfImages/SolutionsOfAssignments', $NameOfFile, 'public');
            
            $solution = Solution::create([
                'solutionFile' => $pathOfFile,
                'user_id' => auth()->user()->id,
                'assignment_id' => $request->assignment_id,
                'user_details' => auth()->user(),
            ], 200);
            return Response::Success($solution, 'Solution has been added successfully', 200);
        } catch (\Exception $e) {
            return Response::Error('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //************************************************************************************************ */
    // public function deleteAssignment(Request $request)
    // {
    //     try {
    //         $assignment = Assignment::find($request->assignment_id);
    //         if (auth()->user()->id == $assignment->user_id) {
    //             return Response::Success($assignment->delete(), 'assignment has been deleted successfully', 200);
    //         }
    //         return Response::Error('You can not delete this', 500);
    //     } catch (\Exception $e) {
    //         return Response::Error(null, 'Something went wrong: ' . $e->getMessage(), 500);
    //     }
    // }
    //************************************************************************************************* */
    public function showSolutions()
    {
        try {
            $data = Solution::where('user_id',auth()->user()->id)->get();
            return Response::Success($data, 'All solutions for this student', 200);
        } catch (\Exception $e) {
            return Response::Error(null, 'Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //************************************************************************************************* */
    public function editSolution(Request $request)
    {
        try {
            $solution = Solution::find($request->solution_id);
            $validator = Validator::make($request->all(), [
                'solutionFile' => 'nullable|file|mimes:pdf|max:3072',
            ]);
            if ($validator->fails()) {
                return Response::Error($validator->errors(), 400);
            }
            $NameOfFile = $request->file('solutionFile')->getClientOriginalName();
            $pathOfFile = $request->file('solutionFile')->storeAs('folderOfImages/SolutionsOfAssignments', $NameOfFile, 'public');

            $solution->solutionFile = $pathOfFile ?? $solution->solutionFile;
            $solution->assignment_id = $solution->assignment_id;
            $solution->user_details = auth()->user();
            $solution->save();
            return Response::Success($solution, 'Solution has been updated successfully', 200);
        } catch (\Exception $e) {
            return Response::Error(null, 'Something went wrong: ' . $e->getMessage(), 500);
        }
    }
}
