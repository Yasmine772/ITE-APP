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
                $validator = Validator::make($request->all(), [
                    'solutionFile' => 'required|file|mimes:pdf|max:3072',
                    'assignment_id' => 'required|string',
                ]);
                if ($validator->fails()) {
                    return Response::Error($validator->errors(), 400);
                }
                $user = User::find(auth()->user()->id);
                $teacher = Teacher::where('user_id', $user->id)->first();
                $assignment = Assignment::find($request->assignment_id);

                if($assignment->teacher_id == $teacher->id)
                {
                    $sameSolution = Solution::where('assignment_id', $request->assignment_id)->first();
                   // return response()->json(['data' => $sameSolution]);
                    if ($request->assignment_id == $sameSolution->assignment_id) {
                        return Response::Error('you add solution for this assignment!', 500);
                    }
                    if (!$request->hasFile('solutionFile')) {
                        return Response::Error('file not found!', 404);
                    }
                    $NameOfFile = $request->file('solutionFile')->getClientOriginalName();
                    $pathOfFile = $request->file('solutionFile')->storeAs('folderOfImages/SolutionsOfAssignments', $NameOfFile, 'public');

                    $solution = Solution::create([
                        'solutionFile' => $pathOfFile,
                        'teacher_id' => $teacher->id,
                        'assignment_id' => $request->assignment_id,
                        'teacher_details' => $user,
                    ], 200);
                    return Response::Success($solution, 'Solution has been added successfully', 200);
                }
                return Response::Error('you can not add solution because you are not responsible about this assignment !', 500);
            }
            return Response::Error('you are not teacher!', 500);
        } catch (\Exception $e) {
            return Response::Error('Something went wrong: ' . $e->getMessage(), 500);
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
                return Response::Success($solution->delete(), 'Solution has been deleted successfully', 200);
            }
            return Response::Error('You can not delete this', 500);
        } catch (\Exception $e) {
            return Response::Error('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //************************************************************************************************* */
    public function showSolutions()
    {
        try {
            return Response::Success(Solution::All(),'All solutions', 200);
        } catch (\Exception $e) {
            return Response::Error('Something went wrong: ' . $e->getMessage(), 500);
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
                    $validator = Validator::make($request->all(), [
                        'solutionFile' => 'nullable|file|mimes:pdf|max:3072',
                    ]);
                    if ($validator->fails()) {
                        return Response::Error($validator->errors(), 400);
                    }
                    $NameOfFile = $request->file('solutionFile')->getClientOriginalName();
                    $pathOfFile = $request->file('solutionFile')->storeAs('folderOfImages/SolutionsOfAssignments', $NameOfFile, 'public');

                    $solution->solutionFile = $pathOfFile ?? $solution->solutionFile;
                    $solution->teacher_id = $teacher->id;
                    $solution->assignment_id = $solution->assignment_id;
                    $solution->teacher_details = $user;
                    $solution->save();
                    return Response::Success($solution, 'Solution has been updated successfully', 200);
                }
                return Response::Error('you can not edit the solution because you are not responsible about this assignment!', 500);
            }
            return Response::Error('you are not teacher!', 500);
        }catch (\Exception $e) {
            return Response::Error('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
}
