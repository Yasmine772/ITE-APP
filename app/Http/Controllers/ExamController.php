<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    use ApiResponseTrait;

    public function addExam(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:50',
                'description' => 'required|string|max:150',
                'duration' => 'required|integer|min:1',
                'subject_id' => 'nullable|string',
                'course_id' => 'nullable|string',
                'course_content_id' => 'nullable|string'
            ]);

            $data = Exam::create([
                'title' => $request->title,
                'description' => $request->description,
                'duration' => $request->duration,
                'user_id' => auth()->user()->id,
                'subject_id' => $request->subject_id ?? null,
                'course_id' => $request->course_id ?? null,
                'course_content_id' => $request->course_content_id ?? null
            ]);
            return response()->json([
                'data' =>  $data,
                'success' => 'Exam has been added successfully'
            ]);

            // $success = 'Exam has been added successfully';
            // $exams = Exam::where('user_id',auth()->user()->id)->get();
            // return view('Exams.showExam', compact('exams', 'success'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Something went wrong: ' . $e->getMessage(), 500);

           //return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
           return $this->errorResponse('Something went wrong: ' . $e->getMessage(), 500);
          //  return redirect()->back()->withErrors('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //************************************************************************************************ */
    public function deleteExam(Request $request)
    {
        try {
            Exam::find($request->exam_id)->delete();

        return response()->json(['data' => 'successfuly']);
        // return view('Exams.showExam')->with('success', 'Exam has been deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //************************************************************************************************* */
    public function editExam(Request $request)
    {
        try {
            $exam = Exam::find($request->exam_id);

            $request->validate([
                'title' => 'nullable|string|max:50',
                'description' => 'nullable|string|max:150',
                'duration' => 'nullable|integer|min:1',
                'subject_id' => 'nullable|string',
                'course_id' => 'nullable|string',
                'course_content_id' => 'nullable|string'
            ]);
            $exam->title = $request->title ?? $exam->title;
            $exam->description = $request->description ?? $exam->description;
            $exam->duration = $request->duration ?? $exam->duration;
            $exam->user_id = auth()->user()->id;
            $exam->subject_id =  $request->subject_id ?? $exam->subject_id;
            $exam->course_id =  $request->course_id ?? $exam->course_id;
            $exam->course_content_id =  $request->course_content_id ?? $exam->course_content_id;
            $exam->save();

            return response()->json([
                'data' =>  $exam,
                'success' => 'Exam has been updated successfully'
            ]);
            // $success = 'Exam has been updated successfully';
            // $exams = Exam::where('user_id',auth()->user()->id)->get();
            // return view('Exams.showExam', compact('exams', 'success'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([$e]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return response()->json([$e]);
            return redirect()->back()->withErrors('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //********************************************************************************************** */
    public function showExamForStudent(Request $request)
    {
        try {
            if($request->subject_id || $request->course_id || $request->course_content_id){
                $exam  = Exam::query();

            if($request->subject_id){
                $exam->where('subject_id',$request->subject_id);
            }
            if ($request->course_id) {
                $exam->where('course_id', $request->course_id);
            }
            if ($request->course_content_id) {
                $exam->where('course_content_id', $request->course_content_id);
            }
                $exam = $exam->get();
                if(!$exam->isEmpty()){
                    return $this->successResponse($exam, 'All exams', 200);
                }
                return $this->errorResponse('No exams yet!', 500);
            }
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //********************************************************************************************** */
    public function detailsOfExam(Request $request)
    {
        try {
            $exam = Exam::find($request->exam_id);
            $questions = $exam->questions()->with('options')->paginate(1);

            return response()->json([
                'exam' =>  $exam,
                'questions'=> $questions
            ]);

        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //********************************************************************************************** */
    public function showExamForTeacher(Request $request)
    {
        try {
            $allData = [];

            $exam = Exam::find($request->exam_id);
            $questions = $exam->questions()->with('options')->paginate(2);

            $allData[] = [
                'exam' =>  $exam,
                'questions' => $questions
            ];

            return view('Exams.showExam', compact('allData'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
}