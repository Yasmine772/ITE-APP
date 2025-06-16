<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Mark;
use App\Response\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class AnswerController extends Controller
{
    public function addAnswer(Request $request)
    {
        try {
            // $statusExam = Mark::where('user_id',auth()->user()->id)
            //                     ->where('exam_id',$request->exam_id)
            //                     ->where('status', 'In_progress')->first();

          //  return Response::Success($statusExam, 200);
            // if($statusExam->status === 'Timeout'){
            //     return Response::Error('you have been taken this exam but the time expired,you can not repeat it!', 500);
            // }
            // if ($statusExam->status === 'Completed') {
            //     return Response::Error('you have been taken this exam', 500);
            // }
            // if ($statusExam->status === 'Cancled') {
            //     return Response::Error('you have been cancled this exam before!', 500);
            // }
            // if ($statusExam->status === 'In_progress')
            // {
                $validator = Validator::make($request->all(), [
                    'question_id' => 'required|string',
                    'option_id' => 'required|string',
                ]);
                if ($validator->fails()) {
                    return Response::Error($validator->errors(), 400);
                }
                $answer  = Answer::create([
                    'user_id' => auth()->user()->id,
                    'question_id' =>  $request->question_id,
                    'option_id' => $request->option_id,
                ]);
                return Response::Success($answer, 'Answer has been added successfully', 200);
           // }
        } catch (\Exception $e) {
            return Response::Error('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
}
