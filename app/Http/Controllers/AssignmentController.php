<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use App\Response\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class AssignmentController extends Controller
{
    public function addAssignment(Request $request)
    {
        try {
            if(auth()->user()->role == 'teacher'){
                $validator = Validator::make($request->all(), [
                    'title' => 'required|string',
                    'file' => 'required|file|mimes:pdf|max:3072',
                    'subject_id' => 'required|string',
                ]);
                if ($validator->fails()) {
                    return Response::Error($validator->errors(), 400);
                }
                $subject = Subject::find($request->subject_id);
                $user = User::find(auth()->user()->id);
                $teacher = Teacher::where('user_id', $user->id)->first();

                //return response()->json(['data' => $teacher]);
                if($subject->teacher_id ==  $teacher->id) 
                {
                    if (!$request->hasFile('file')) {
                        return Response::Error('File not found!',404);
                    }
                    $NameOfFile = $request->file('file')->getClientOriginalName();
                    $pathOfFile = $request->file('file')->storeAs('folderOfImages/Assignments', $NameOfFile, 'public');

                    $assignment = Assignment::create([
                        'title' => $request->title,
                        'file' => $pathOfFile,
                        'teacher_id' => $teacher->id,
                        'subject_id' => $request->subject_id,
                    ], 200);
                    return Response::Success($assignment, 'Assignment has been added successfully', 200);
                }
                return Response::Error('you are not responsible about this assignment !', 500);
            }
            return Response::Error('you are not teacher!', 500);
        } catch (\Exception $e) {
            return Response::Error(null, 'Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //************************************************************************************************ */
    public function deleteAssignment(Request $request)
    {
        try {
            $assignment = Assignment::find($request->assignment_id);
            $user = User::find(auth()->user()->id);
            $teacher = Teacher::where('user_id', $user->id)->first();

            if ($teacher->id == $assignment->teacher_id) {
                return Response::Success($assignment->delete(), 'Assignment has been deleted successfully', 200);
            }
            return Response::Error('Are you reposible about this assignment ? You can not delete this', 500);
        } catch (\Exception $e) {
            return Response::Error('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //************************************************************************************************* */
    public function showAssignment(Request $request)
    {
        try {
            $assignments = Assignment::where('subject_id',$request->subject_id)->get();
            return Response::Success($assignments, 'All assignments for this subject', 200);
        } catch (\Exception $e) {
            return Response::Error('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //************************************************************************************************* */
    public function editAssignment(Request $request)
    {
        try {
            if (auth()->user()->role == 'teacher')
            {
                $assignment = Assignment::find($request->assignment_id);
                $user = User::find(auth()->user()->id);
                $teacher = Teacher::where('user_id', $user->id)->first();

                if ($assignment->teacher_id == $teacher->id) 
                {
                $validator = Validator::make($request->all(), [
                    'title' => 'nullable|string',
                    'file' => 'nullable|file|mimes:pdf|max:3072',
                ]);
                if ($validator->fails()) {
                     return Response::Error($validator->errors(), 400);
                }
                if($request->hasFile('file')){
                    $NameOfFile = $request->file('file')->getClientOriginalName();
                    $pathOfFile = $request->file('file')->storeAs('folderOfImages/Assignments', $NameOfFile, 'public');
                    $assignment->file =  $pathOfFile ?? $assignment->file;
                }
                $assignment->title = $request->title ?? $assignment->title;
                $assignment->teacher_id = $teacher->id;
                $assignment->subject_id = $assignment->subject_id;
                $assignment->save();
                return Response::Success($assignment, 'Assignment has been updated successfully', 200);
                }
                return Response::Error('you are not responsible about this assignment !', 500);
            }
            return Response::Error('you are not teacher!', 500);
        } catch (\Exception $e) {
            return Response::Error(null, 'Something went wrong: ' . $e->getMessage(), 500);
        }
    }
}