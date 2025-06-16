<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function addQuestion(Request $request)
    {
        try {
            $request->validate([
                'question_text' => 'required|string|max:200',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'mark' => 'nullable|string',
                'exam_id' => 'required|string',
            ]);

            if ($request->hasFile('photo')) {
                $NameOfPhoto = $request->file('photo')->getClientOriginalName();
                $pathOfPhoto = $request->file('photo')->storeAs('folderOfImages/Questions', $NameOfPhoto, 'public');
            }
            $pathOfPhoto = 'null';

            $exist = Question::where('question_text', $request->question_text)
                            ->where('photo', $pathOfPhoto)
                            ->exists();
            if ($exist) {
                //   return redirect()->back()->withErrors('Question is exist!');
                return response()->json(['message' => 'Question is exist']);
            }
            $question  = Question::create([
                'question_text' => $request->question_text,
                'photo' => $pathOfPhoto, 
                'mark' => $request->mark,
                'exam_id' => $request->exam_id,
            ]);

            return response()->json([
                'data' =>  $question,
                'success' => 'Question has been added successfully'
            ]);
            // $success = 'Question has been added successfully';
            // $exams = Exam::where('user_id',auth()->user()->id)->get();
            // return view('Exams.showExam', compact('exams', 'success'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //************************************************************************************************ */
    public function deleteQuestion(Request $request)
    {
        try {
            Question::find($request->question_id)->delete();

            return response()->json([
                'data' => 'success',
            ]);

            // return view('Exams.showQuestions')->with('success', 'Question has been deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
//************************************************************************************************* */
    public function editQuestion(Request $request)
    {
        try {
            $question = Question::find($request->question_id);

            $request->validate([
                'question_text' => 'nullable|string|max:200',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'mark' => 'nullable|string',
            ]);


            if ($request->hasFile('photo')) {
                $NameOfPhoto = $request->file('photo')->getClientOriginalName();
                $pathOfPhoto = $request->file('photo')->storeAs('folderOfImages/Questions', $NameOfPhoto, 'public');
            }

            $exist = Question::where('question_text', $request->question_text)
                            ->where('photo', $pathOfPhoto)
                            ->exists();
            if ($exist) {
                //   return redirect()->back()->withErrors('Question is exist!');
                return response()->json(['message' => 'Question is exist']);
            }
            $question->question_text = $request->question_text ?? $question->question_text;
            $question->photo = $pathOfPhoto ?? $question->photo;
            $question->mark = $request->mark ?? $question->mark;
            $question->exam_id =  $question->exam_id;
            $question->save();

            return response()->json([
                'data' =>  $question,
                'success' => 'question has been updated successfully'
            ]);

            // $success = 'Question has been updated successfully';
            // $questions = Question::where('exam_id', $request->exam_id)->get();
            // return view('Exams.showQuestions', compact('questions', 'success'));

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
}
