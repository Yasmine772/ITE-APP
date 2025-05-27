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
                'content' => 'required|string|min:50|max:10000|regex:/^[\p{Arabic}a-zA-Z0-9\s.,\-_\!\؟\?]+$/u',
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
            return Response::Error(null, 'Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //*********************************************************************************************** */
    public function deleteComplaint(Request $request)
    {
        try {
            $complaint = Complaint::find($request->complaint_id);
            if (auth()->user()->id == $complaint->user_id) {
                return Response::Success($complaint->delete(), 'Complaint has been deleted successfully', 200);
            }
            return Response::Error('You can not delete this', 500);
        } catch (\Exception $e) {
            return Response::Error(null, 'Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //************************************************************************************************* */
    public function showComplaintes()
    {
        try {
            return Response::Success(Complaint::all(), 'All complaints', 200);
        } catch (\Exception $e) {
            return Response::Error(null, 'Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //*************************************************************************************************** */
    public function editComplaint(Request $request)
    {
        try {
            $complaint = Complaint::find($request->complaint_id);
            $validator = Validator::make($request->all(), [
                'content' => 'nullable|string|min:50|max:10000|regex:/^[\p{Arabic}a-zA-Z0-9\s.,\-_\!\؟\?]+$/u',
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
            return Response::Error(null, 'Something went wrong: ' . $e->getMessage(), 500);
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
