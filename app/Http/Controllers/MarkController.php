<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Exam;
use App\Models\Mark;
use App\Models\Option;
use App\Models\Question;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class MarkController extends Controller
{
    use ApiResponseTrait;
    
    public function startExam(Request $request) 
    {
        try {
            $examExist = Mark::where('user_id',auth()->user()->id)
                            ->where('exam_id',$request->exam_id)
                            ->whereIn('status',['In_progress', 'Completed', 'Timeout'])->first();
     
            if($examExist){
                return $this->errorResponse('you have been taken this exam before!', 500);
            }
            $start_exam = Mark::create([
                'user_id' => auth()->user()->id,
                'exam_id' => $request->exam_id,
                'start_time' => time(),
                'status' => 'In_progress',
            ]);
            
            return $this->successResponse($start_exam,'Exam start!', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
//************************************************************************************************* */
    public function finishExam(Request $request)
    {
        try {
            $examInProgress = Mark::where('user_id',auth()->user()->id)
                                    ->where('exam_id', $request->exam_id)
                                    ->where('status', 'In_progress')->first();

            if(!$examInProgress){
                return $this->errorResponse('you have not any active trying for this exam!', 500);
            }

            $exam = Exam::find($request->exam_id);
            $due_mark = 0;
            $answers = Answer::where('user_id', auth()->user()->id)->get();

            $minutesPassed = floor((time() - $examInProgress->start_time)/60);

            if($minutesPassed > $exam->duration){

                foreach ($answers as  $answer) {
                    $option = Option::find($answer->option_id);
                    if ($option->is_correct === 1) {
                        $question = Question::find($option->question_id);
                        $due_mark += $question->mark;
                    }
                }
                $examInProgress->due_mark = $due_mark;
                $examInProgress->status = 'Timeout';
                $examInProgress->end_time = time();
                $examInProgress->save();
                return $this->errorResponse('Timeout! minutesPassed :'. $minutesPassed .' minutes ,the mark : '.$due_mark.' only ', 500);
            }

            if ($answers->isEmpty()) {
                $examInProgress->due_mark = 0 ;
                $examInProgress->end_time = time();
                $examInProgress->status = 'Completed';
                $examInProgress->save();
                return $this->errorResponse('Your mark is 0!', 500);
            }
            foreach ($answers as  $answer) {
                $option = Option::find($answer->option_id);
                if ($option->is_correct === 1) {
                    $question = Question::find($option->question_id);
                    $due_mark += $question->mark;
                }
            } 
                $examInProgress->due_mark = $due_mark;
                $examInProgress->end_time = time();
                $examInProgress->status = 'Completed';
                $examInProgress->save();
                return $this->successResponse($examInProgress, 'Exam of user', 200);
                
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
}
