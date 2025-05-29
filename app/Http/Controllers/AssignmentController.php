<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use App\Response\Response;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function addAssignment(Request $request)
    {
        try {
            if(auth()->user()->role == 'teacher'){
                $request->validate([
                    'title' => 'required|string',
                    'file' => 'required|file|mimes:pdf|max:3072',
                    'subject_id' => 'required|string',
                ]);
                $subject = Subject::find($request->subject_id);
                $user = User::find(auth()->user()->id);
                $teacher = Teacher::where('user_id', $user->id)->first();

                if($subject->teacher_id ==  $teacher->id) 
                {
                    $NameOfFile = $request->file('file')->getClientOriginalName();
                    $pathOfFile = $request->file('file')->storeAs('folderOfImages/Assignments', $NameOfFile, 'public');

                    $assignment = Assignment::create([
                        'title' => $request->title,
                        'file' => $pathOfFile,
                        'teacher_id' => $teacher->id,
                        'subject_id' => $request->subject_id,
                    ]);

                    $success = 'Assignment has been added successfully';
                    $assignments = assignment::where('subject_id', $request->subject_id)->get();
                    return view('assignments.All_assignments', compact('assignments', 'success'));
                }
                return redirect()->back()->withErrors('you are not responsible about this subject !');
            }
            return redirect()->back()->withErrors('You are not a teacher!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Something went wrong: ' . $e->getMessage(), 500);
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
                $assignment->delete();
                return view('assignments.All_assignments')->with('success', 'Assignment has been deleted successfully');
            }
            return redirect()->back()->withErrors('Are you reposible about this assignment ? You can not delete this');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //************************************************************************************************* */
    public function showAssignment(Request $request)
    {
        try {
            $assignments = Assignment::where('subject_id',$request->subject_id)->get();
            return view('assignments.All_assignments', compact('assignments'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Something went wrong: ' . $e->getMessage(), 500);
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
                    $request->validate([
                    'title' => 'nullable|string',
                    'file' => 'nullable|file|mimes:pdf|max:3072',
                ]);
                if($request->hasFile('file')){
                    $NameOfFile = $request->file('file')->getClientOriginalName();
                    $pathOfFile = $request->file('file')->storeAs('folderOfImages/Assignments', $NameOfFile, 'public');
                    $assignment->file =  $pathOfFile ?? $assignment->file;
                }
                $assignment->title = $request->title ?? $assignment->title;
                $assignment->teacher_id = $teacher->id;
                $assignment->subject_id = $assignment->subject_id;
                $assignment->save();

                $success = 'Assignment has been updated successfully';
                $assignments = assignment::where('subject_id', $request->subject_id)->get();
                return view('assignments.All_assignments', compact('assignments', 'success'));
                }
                return redirect()->back()->withErrors('you are not responsible about this assignment!');
            }
            return redirect()->back()->withErrors('You are not a teacher!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //********************************************************************************************* */
    public function displayAssignment(Request $request)
    {
        try {
            $assignments = Assignment::where('subject_id', $request->subject_id)->get();
            return Response::Success($assignments, 'All assignments for this subject', 200);
        } catch (\Exception $e) {
            return Response::Error('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
}