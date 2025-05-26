<?php

namespace App\Http\Controllers;

use App\Models\Advice;
use App\Models\Subject;
use App\Models\Teacher;
use App\Response\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class AdviceController extends Controller
{
    //Only teacher can add , delete, edit advice
    public function addAdvice(Request $request)
    {
        try {
            if(auth()->user()->role == 'teacher')
            {
                $subject = Subject::find($request->subject_id);
                $teacher = Teacher::where('user_id', auth()->user()->id)->first();
                if ($subject->teacher_id ==  $teacher->id) 
                {
                    $validator = Validator::make($request->all(), [
                        'content' => 'required|string|min:50|max:10000|regex:/^[\p{Arabic}a-zA-Z0-9\s.,\-_\!\؟\?]+$/u',
                        'subject_id' => 'required|string',
                    ]);
                    if ($validator->fails()) {
                        return Response::Error($validator->errors(), 400);
                    }
                    $advice = Advice::create([
                        'content' => $request->content,
                        'teacher_id' => $teacher->id,
                        'subject_id' => $request->subject_id,
                    ], 200);
                    return Response::Success($advice, 'Advice has been added successfully', 200);
                }
                return Response::Error('you are not responsible about this subject !', 500);
            }
            return Response::Error('you are not teacher!', 500);
        } catch (\Exception $e) {
            return Response::Error('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //************************************************************************************************ */
    public function deleteAdvice(Request $request)
    {
        try {
            $advice = Advice::find($request->advice_id);
            if (auth()->user()->id == $advice->teacher_id) {
                return Response::Success($advice->delete(), 'Advice has been deleted successfully', 200);
            }
            return Response::Error('You are not reponsible about this advice !', 500);
        } catch (\Exception $e) {
            return Response::Error(null, 'Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //************************************************************************************************* */
    public function showAdvices(Request $request)
    {
        try {
            return Response::Success(Advice::where('subject_id',$request->subject_id)->get(), 'All advices for this subject', 200);
        } catch (\Exception $e) {
            return Response::Error(null, 'Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //************************************************************************************************* */
    public function editAdvices(Request $request)
    {
        try {
            $advice = Advice::find($request->advice_id);
            if (auth()->user()->role == 'teacher')
            {
                if (auth()->user()->id == $advice->teacher_id) 
                {
                    $validator = Validator::make($request->all(), [
                        'content' => 'nullable|string|min:50|max:10000|regex:/^[\p{Arabic}a-zA-Z0-9\s.,\-_\!\؟\?]+$/u',
                    ]);
                    if ($validator->fails()) {
                        return Response::Error($validator->errors(), 400);
                    }
                    $advice->content = $request->content ?? $advice->content;
                    $advice->teacher_id = $advice->teacher_id;
                    $advice->subject_id = $advice->subject_id;
                    $advice->save();
                    return Response::Success($advice, 'Advice has been updated successfully', 200);
                }
                return Response::Error('you are not responsible about this subject !', 500);
            }
            return Response::Error('you are not teacher!', 500);
        } catch (\Exception $e) {
            return Response::Error(null, 'Something went wrong: ' . $e->getMessage(), 500);
        }
    }
}