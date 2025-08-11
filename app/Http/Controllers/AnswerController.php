<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Validator;


class AnswerController extends Controller
{
    use ApiResponseTrait;

    public function addAnswer(Request $request)
    {
        try {
                $validator = Validator::make($request->all(), [
                    'question_id' => 'required|string',
                    'option_id' => 'required|string',
                ]);
                if ($validator->fails()) {
                    return $this->errorResponse($validator->errors(), 400);
                }
                $answer  = Answer::create([
                    'user_id' => auth()->user()->id,
                    'question_id' =>  $request->question_id,
                    'option_id' => $request->option_id,
                ]);
                return $this->successResponse($answer, 'Answer has been added successfully', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
}
