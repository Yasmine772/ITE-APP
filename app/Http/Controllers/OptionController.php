<?php

namespace App\Http\Controllers;

use App\Models\Option;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function addOption(Request $request)
    {
        try {
            $request->validate([
                'answer_text' => 'required|string|max:150',
                'is_correct' => 'nullable|in:0,1',
                'question_id' => 'required|string',
            ]);

            $options = Option::where('question_id', $request->question_id)->get();

            if (count($options) !== 3 ) {
                return response()->json([
                    'error' => 'You must provide exactly 3 answers!'
                ]);
                //   return redirect()->back()->withErrors('There should not be answers greater than 3!');
            }
            if ($options) {
                foreach ($options as $option) {

                    if ($option->answer_text === $request->answer_text) {
                        //   return redirect()->back()->withErrors('Question is exist!');
                        return response()->json(['message' => 'Option is exist']);
                    }

                    if ($request->is_correct && $option->is_correct === 1) {
                        return response()->json([
                            'error' => 'There should not be two correct answer!'
                        ]);
                        //   return redirect()->back()->withErrors('There should not be two correct answer!');
                    }
                }
            }
            $option  = Option::create([
                'answer_text' => $request->answer_text,
                'is_correct' => $request->is_correct,
                'question_id' => $request->question_id,
            ]);
            return response()->json([
                'data' =>  $option,
                'success' => 'option has been added successfully'
            ]);

            // $success = 'Option has been added successfully';
            // $options = Option::where('question_id',$request->question_id)->get();
            // return view('Exams.showExam', compact('options', 'success'));

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => $e->errors()]);
            // return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return response()->json(['message' =>  $e->getMessage()]);
            // return redirect()->back()->withErrors('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //************************************************************************************************ */
    public function deleteOption(Request $request)
    {
        try {
            Option::find($request->option_id)->delete();

            return response()->json([
                'data' => 'success',
            ]);
            // return redirect()->with('success', 'Option has been deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //************************************************************************************************* */
    public function editOption(Request $request)
    {
        try {
            $option = Option::find($request->option_id);

            $request->validate([
                'answer_text' => 'nullable|string|max:150',
                'is_correct' => 'nullable|in:0,1',
            ]);

            $options = Option::where('question_id', $option->question_id)->get();

            foreach ($options as $option) {

                if ($option->answer_text === $request->answer_text) {
                    //   return redirect()->back()->withErrors('Question is exist!');
                    return response()->json(['message' => 'Option is exist']);
                }

                if ($request->is_correct && $option->is_correct === 1) {
                    return response()->json([
                        'error' => 'There should not be two correct answer!'
                    ]);
                    //   return redirect()->back()->withErrors('There should not be two correct answer!');
                }
               
            }
            $option->answer_text = $request->answer_text ?? $option->answer_text;
            $option->is_correct = $request->is_correct ?? $option->is_correct;
            $option->question_id =  $option->question_id;
            $option->save();

            return response()->json([
                'data' =>  $option,
                'success' => 'option has been updated successfully'
            ]);

            // $success = 'Option has been updated successfully';
            // $options = Option::where('question_id', $request->question_id)->get();
            // return view('Exams.showExam', compact('options', 'success'));

        } catch (\Illuminate\Validation\ValidationException $e) {
            // return redirect()->back()->withErrors($e->errors())->withInput();
            return response()->json(['message' => $e->errors()]);
        } catch (\Exception $e) {
            return response()->json(['message' =>  $e->getMessage()]);
            // return redirect()->back()->withErrors('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
}
