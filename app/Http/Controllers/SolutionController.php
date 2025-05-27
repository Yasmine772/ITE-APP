<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Solution;
use App\Models\Teacher;
use App\Models\User;
use App\Response\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class SolutionController extends Controller
{
    public function addSolution(Request $request)
    {
        try {
            if(auth()->user()->role == 'teacher')
            {
                $request->validate([
                    'solutionFile' => 'required|file|mimes:pdf|max:3072',
                    'assignment_id' => 'required|string',
                ]);
                $user = User::find(auth()->user()->id);
                $teacher = Teacher::where('user_id', $user->id)->first();
                $assignment = Assignment::find($request->assignment_id);

                if($assignment->teacher_id == $teacher->id)
                {
                    $sameSolution = Solution::where('assignment_id', $request->assignment_id)->first();
                    if ($request->assignment_id == $sameSolution->assignment_id) {
                         return redirect()->back()->withErrors('you add solution for this assignment!');
                    }
                    $NameOfFile = $request->file('solutionFile')->getClientOriginalName();
                    $pathOfFile = $request->file('solutionFile')->storeAs('folderOfImages/SolutionsOfAssignments', $NameOfFile, 'public');

                    $solution = Solution::create([
                        'solutionFile' => $pathOfFile,
                        'teacher_id' => $teacher->id,
                        'assignment_id' => $request->assignment_id,
                        'teacher_details' => $user,
                    ]);
                    $success = 'Solution has been added successfully';
                    $solutions = Solution::where('assignment_id', $request->assignment_id)->get();
                    return view('Solutions.All_solutions', compact('solutions', 'success'));
                }
                return redirect()->back()->withErrors('you can not add solution because you are not responsible about this assignment !');
            }
            return redirect()->back()->withErrors('You are not a teacher!');
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

            $user = User::find(auth()->user()->id);
            $teacher = Teacher::where('user_id', $user->id)->first();

            if ($solution->teacher_id == $teacher->id) 
            {
                $solution->delete();
                return view('Solutions.All_solutions')->with('success', 'Solution has been deleted successfully');
            }
            return redirect()->back()->withErrors('You can not delete this');
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

            if (auth()->user()->role == 'teacher') 
            {
                $user = User::find(auth()->user()->id);
                $teacher = Teacher::where('user_id', $user->id)->first();

                if ($solution->teacher_id == $teacher->id)
                {
                    $request->validate([
                        'solutionFile' => 'nullable|file|mimes:pdf|max:3072',
                    ]);
                    $NameOfFile = $request->file('solutionFile')->getClientOriginalName();
                    $pathOfFile = $request->file('solutionFile')->storeAs('folderOfImages/SolutionsOfAssignments', $NameOfFile, 'public');

                    $solution->solutionFile = $pathOfFile ?? $solution->solutionFile;
                    $solution->teacher_id = $teacher->id;
                    $solution->assignment_id = $solution->assignment_id;
                    $solution->teacher_details = $user;
                    $solution->save();

                    $success = 'Solution has been updated successfully';
                    $solutions = Solution::where('assignment_id', $request->assignment_id)->get();
                    return view('solutions.All_solutions', compact('solutions', 'success'));
                }
                return redirect()->back()->withErrors('you can not edit the solution because you are not responsible about this assignment!');
            }
            return redirect()->back()->withErrors('You are not a teacher!');
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
            $solutions = Solution::where('assignment_id', $request->assignment_id)->get();
            return Response::Success($solutions, 'All solutions', 200);
        } catch (\Exception $e) {
            return Response::Error('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
}
