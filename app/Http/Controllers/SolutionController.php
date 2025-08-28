<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Solution;
use App\Models\Teacher;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;


class SolutionController extends Controller
{
    use ApiResponseTrait;
    
    public function addSolution(Request $request)
    {
        try {
            $request->validate([
                'solutionFile' => 'required|file|mimes:pdf|max:3072',
                'assignment_id' => 'required|string',
            ]);
            $teacher = Teacher::where('user_id', auth()->user()->id)->first();

            $assignment = Assignment::find($request->assignment_id);
            // $sameSolution = Solution::where('assignment_id', $request->assignment_id)->first();
            // if ($request->assignment_id == $sameSolution->assignment_id) {
            //     return 'error';
            //     return redirect()->back()->withErrors('you add solution for this assignment!');
            // }
            $sameSolution = Solution::where('assignment_id', $request->assignment_id)->first();
            if ($sameSolution) {
                return redirect()->back()->withErrors('you add solution for this assignment!');
            }

            $NameOfFile = $request->file('solutionFile')->getClientOriginalName();
            $pathOfFile = $request->file('solutionFile')->storeAs('folderOfImages/SolutionsOfAssignments', $NameOfFile, 'public');
            $solution = Solution::create([
                'solutionFile' => $pathOfFile,
                'teacher_id' => $teacher->id,
                'assignment_id' => $request->assignment_id,
                'teacher_details' => auth()->user(),
            ]);
            $success = 'Solution has been added successfully';
            $solutions = Solution::where('assignment_id', $request->assignment_id)->get();
            return response()->json(['data' => 'ok']);
           // view('Solutions.All_solutions', compact('solutions', 'success'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //************************************************************************************************ */
    public function deleteSolution(Request $request)
    {
        try {
            $solution = Solution::find($request->solution_id);
            $solution->delete();
            return redirect()->back()->with('success', 'Solution has been deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //************************************************************************************************* */
    public function showSolutions(Request $request)
    {
        try {
            $solutions = Solution::where('assignment_id', $request->assignment_id)->get();
            return view('Solutions.All_solutions', compact('solutions'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //************************************************************************************************* */
    public function editSolution(Request $request)
    {
        try {
            $solution = Solution::find($request->solution_id);
            $teacher = Teacher::where('user_id', auth()->user()->id)->first();

            $request->validate([
                'solutionFile' => 'nullable|file|mimes:pdf|max:3072',
            ]);
            $NameOfFile = $request->file('solutionFile')->getClientOriginalName();
            $pathOfFile = $request->file('solutionFile')->storeAs('folderOfImages/SolutionsOfAssignments', $NameOfFile, 'public');

            $solution->solutionFile = $pathOfFile ?? $solution->solutionFile;
            $solution->teacher_id = $teacher->id;
            $solution->assignment_id = $solution->assignment_id;
            $solution->teacher_details = auth()->user();
            $solution->save();

            $success = 'Solution has been updated successfully';
            $solutions = Solution::where('assignment_id', $request->assignment_id)->get();
            return view('solutions.All_solutions', compact('solutions', 'success'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //*************************************************************************************************** */
    public function displaySolutions(Request $request)
    {
        try {
            $solutions = Solution::where('assignment_id', $request->assignment_id)->first();
            if($solutions){
                return $this->successResponse($solutions, 'A solution for this assignment', 200);
            }
            return $this->errorResponse('No solution yet!', 500);
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //********************************************************************************************* */
    public function displaySolutionsdetails(Request $request)
    {
        try {
            $solution = Solution::find($request->solution_id);
            return $this->successResponse($solution, 'Solution details ', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
}
