<?php

namespace App\Http\Controllers;

use App\Models\Advice;
use App\Models\Subject;
use App\Models\Teacher;
use App\Response\Response;
use Illuminate\Http\Request;

class AdviceController extends Controller
{
    public function addAdvice(Request $request)
    {
        try {
            if(auth()->user()->role !== 'teacher'){
                return redirect()->back()->withErrors('You are not a teacher!');
            }
                $subject = Subject::find($request->subject_id);
                $teacher = Teacher::where('user_id', auth()->user()->id)->first();

                if ($subject->teacher_id !==  $teacher->id) {
                return redirect()->back()->withErrors('you are not responsible about this subject !');
                }
                    $request->validate([
                        'content' => 'required|string|min:50|max:10000|regex:/^[\p{Arabic}a-zA-Z0-9\s.,\-_\!\؟\?]+$/u',
                        'subject_id' => 'required|string',
                    ]);
                    $advice = Advice::create([
                        'content' => $request->content,
                        'teacher_id' => $teacher->id,
                        'subject_id' => $request->subject_id,
                    ]);
                    $success = 'Advice has been added successfully';
                    $advices = Advice::where('subject_id',$request->subject_id)->get();
                    return view('advices.All_Advices',compact('advices', 'success'));

            } catch (\Illuminate\Validation\ValidationException $e) {
                    return redirect()->back()->withErrors($e->errors())->withInput();
            } catch (\Exception $e) {
                    return redirect()->back()->withErrors('Something went wrong: ' . $e->getMessage(), 500);
            }           
    }
    //************************************************************************************************ */
    public function deleteAdvice($advice_id)
    {
        try {
            $advice = Advice::find($advice_id);
            if (auth()->user()->id == $advice->teacher_id) {
                $advice->delete();
                return view('advices.All_Advices')->with('success', 'Advice has been deleted successfully');
            }
            return redirect()->back()->withErrors('You are not reponsible about this advice !');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //************************************************************************************************* */
    public function showAdvices(Request $request)
    {
        try {
            $advices = Advice::where('subject_id', $request->subject_id)->get();
            return view('advices.All_Advices',compact('advices'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Something went wrong: ' . $e->getMessage(), 500);
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
                    $request->validate([
                        'content' => 'nullable|string|min:50|max:10000|regex:/^[\p{Arabic}a-zA-Z0-9\s.,\-_\!\؟\?]+$/u',
                    ]);
                    $advice->content = $request->content ?? $advice->content;
                    $advice->teacher_id = $advice->teacher_id;
                    $advice->subject_id = $advice->subject_id;
                    $advice->save();

                    $success = 'Advice has been updated successfully';
                    $advices = Advice::where('subject_id', $request->subject_id)->get();
                    return view('advices.All_Advices', compact('advices', 'success'));
                }
                return redirect()->back()->withErrors('you are not responsible about this subject!');
            }
            return redirect()->back()->withErrors('you are not teacher!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //********************************************************************************************** */
    public function displayAdvices(Request $request)
    {
        try {
            $advices = Advice::where('subject_id', $request->subject_id)->get();
            if($advices->isEmpty()){
                return Response::Error('No advices yet!', 500);
            }
            return Response::Success($advices, 'All advices for this subject', 200);
        } catch (\Exception $e) {
            return Response::Error('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
}