<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ComplaintController extends Controller
{
    use ApiResponseTrait;

    public function addComplaint(Request $request)
   {
        try {
            $validator = Validator::make($request->all(), [
                'content' => 'required|string|min:50|max:10000|regex:/^[\p{Arabic}a-zA-Z0-9\s.,\-_:;()@!?؟\n؛]+$/u',
            ]);
            if ($validator->fails()) {
                return $this->errorResponse('error',$validator->errors(), 400);
            }
            $complaint = Complaint::create([
                'content' => $request->content,
                'user_id' => auth()->user()->id,
                'user_details' => json_encode(auth()->user()->only(['name', 'profile_photo_path'])),
            ]);
            return $this->successResponse($complaint, 'Complaint has been added successfully',200);

        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong: '. $e->getMessage(),null, 500);
        }
}
    //*********************************************************************************************** */
    public function deleteComplaint(Request $request)
    {
        try {
            Complaint::find($request->complaint_id)->delete();
            return $this->successResponse(null,'Complaint has been deleted successfully', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //************************************************************************************************* */
    public function showComplaintes()
    {
        try {
            $complaints = Complaint::where('user_id',auth()->user()->id)->get();
            if($complaints->isEmpty()){
                return $this->errorResponse('you have not add any complaints yet!', 500);
            }
            return $this->successResponse($complaints, 'All complaints for this user', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong: ' . $e->getMessage(), 500);
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
                    return $this->errorResponse($validator->errors(), 400);
                }
            $complaint->content = $request->content ?? $complaint->content;
            $complaint->user_id = auth()->user()->id;
            $complaint->user_details = json_encode(auth()->user()->only(['name', 'profile_photo_path']));
            $complaint->save();
            return $this->successResponse($complaint, 'Complaint has been updated successfully', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //************************************************************************************************** */
    public function complaintDetails(Request $request)
    {
        try {
            return $this->successResponse(Complaint::find($request->complaint_id), 'Complaint details:', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong: ' . $e->getMessage(), 500);
        }
    }

}
