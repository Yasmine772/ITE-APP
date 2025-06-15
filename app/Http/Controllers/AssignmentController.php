<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Teacher;
use App\Response\Response;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function addAssignment(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string',
                'file' => 'required|file|mimes:pdf|max:3072',
                'subject_id' => 'required|string',
            ]);
            $teacher = Teacher::where('user_id', auth()->user()->id)->first();

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
            $assignment->delete();
            return redirect()->back()->with('success', 'Assignment has been deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //************************************************************************************************* */
    public function showAssignment(Request $request)
    {
        try {
            $assignments = Assignment::where('subject_id', $request->subject_id)->get();
            return view('assignments.All_assignments', compact('assignments'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //************************************************************************************************* */
    public function editAssignment(Request $request)
    {
        try {
            $assignment = Assignment::find($request->assignment_id);
            $teacher = Teacher::where('user_id', auth()->user()->id)->first();

            $request->validate([
                'title' => 'nullable|string',
                'file' => 'nullable|file|mimes:pdf|max:3072',
            ]);
            if ($request->hasFile('file')) {
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
            if($assignments->isEmpty()){
                return Response::Error('There are not assignments yet!', 500);
            }
            return Response::Success($assignments, 'All assignments for this subject', 200);
        } catch (\Exception $e) {
            return Response::Error('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //********************************************************************************************* */
    public function displayAssignmentdetails(Request $request)
    {
        try {
            $assignment = Assignment::find($request->assignment_id);
            return Response::Success($assignment, 'Assignment details ', 200);
        } catch (\Exception $e) {
            return Response::Error('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
}
