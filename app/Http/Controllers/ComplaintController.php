<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Response\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ComplaintController extends Controller
{
   public function addComplaint(Request $request)
   {
        try {
            $validator = Validator::make($request->all(), [
                'content' => 'required|string|min:50|max:10000|regex:/^[\p{Arabic}a-zA-Z0-9\s.,\-_:;()@!?؟\n؛]+$/u',
            ]);
            if ($validator->fails()) {
                return Response::Error($validator->errors(), 400);
            }
            $complaint = Complaint::create([
                'content' => $request->content,
                'user_id' => auth()->user()->id,
                'user_details' => auth()->user(),
            ], 200);
            return Response::Success($complaint , 'Complaint has been added successfully', 200);
        } catch (\Exception $e) {
            return Response::Error('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //*********************************************************************************************** */
    public function deleteComplaint(Request $request)
    {
        try {
            Complaint::find($request->complaint_id)->delete();
            return Response::Success(null,'Complaint has been deleted successfully', 200);
        } catch (\Exception $e) {
            return Response::Error('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //************************************************************************************************* */
    public function showComplaintes()
    {
        try {
            $complaints = Complaint::where('user_id',auth()->user()->id)->get();
            if($complaints->isEmpty()){
                return Response::Error('you have not add any complaints yet!', 500);
            }
            return Response::Success($complaints, 'All complaints for this user', 200);
        } catch (\Exception $e) {
            return Response::Error('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //*************************************************************************************************** */
    public function editComplaint(Request $request)
    {
        try {
            $complaint = Complaint::find($request->complaint_id);
            $validator = Validator::make($request->all(), [
                'content' => 'nullable|string|min:50|max:10000|regex:/^[\p{Arabic}a-zA-Z0-9\s.,\-_:;()@!?؟\n؛]+$/u',
                ]);
                if ($validator->fails()) {
                    return Response::Error($validator->errors(), 400);
                }
            $complaint->content = $request->content ?? $complaint->content;
            $complaint->user_id = auth()->user()->id;
            $complaint->user_details = auth()->user();
            $complaint->save();
            return Response::Success($complaint, 'Complaint has been updated successfully', 200);
        } catch (\Exception $e) {
            return Response::Error('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //************************************************************************************************** */
    public function complaintDetails(Request $request)
    {
        try {
            return Response::Success(Complaint::find($request->complaint_id), 'Complaint details:', 200);
        } catch (\Exception $e) {
            return Response::Error('Something went wrong: ' . $e->getMessage(), 500);
        }
    }

}
