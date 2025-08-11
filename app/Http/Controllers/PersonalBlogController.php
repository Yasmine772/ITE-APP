<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonalBlogRequest;
use App\Http\Requests\PersonalBlogUpdateRequest;
use App\Models\PersonalBlog;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class PersonalBlogController extends Controller
{
    use ApiResponseTrait;
   public function index(): \Illuminate\Http\JsonResponse
   {
       $user = auth()->user();
       $personalBlogs = $user->personalBlogs()->get();
       return $this->successResponse($personalBlogs);
   }
   public function store(PersonalBlogRequest $request): \Illuminate\Http\JsonResponse
   {
       try{
           $user = auth()->user();
           $personalBlog = $user->personalBlogs()->create($request->validated());
           return $this->successResponse($personalBlog,'Your notes has been saved successfully',200);
       }
       catch (\Exception $exception){
           return $this->errorResponse($exception->getMessage(),400);
       }
   }
   public function update(PersonalBlogUpdateRequest $request,$id): \Illuminate\Http\JsonResponse
   {
     try{
         $user_id = auth()->user()->id;
         $personalBlog = PersonalBlog::findOrFail($id);
         if($personalBlog->user_id != $user_id){
            return $this->errorResponse('Unauthorized',403);
         }
         $personalBlog->update($request->validated());
         return $this->successResponse($personalBlog,'Your notes has been updated successfully',200);
     }
     catch (\Exception $exception){
         return $this->errorResponse($exception->getMessage(),400);
     }
   }
     public function destroy($id): \Illuminate\Http\JsonResponse
     {
         try{
             $user_id = auth()->user()->id;
             $personalBlog = PersonalBlog::findOrFail($id);
             if($personalBlog->user_id != $user_id){
                 return $this->errorResponse('Unauthorized',403);
             }
             $personalBlog->delete();
             return $this->successResponse($personalBlog,'Your notes has been deleted successfully',200);
         }
         catch (\Exception $exception){
             return $this->errorResponse($exception->getMessage(),400);
         }
   }
   public function destroyAll(): \Illuminate\Http\JsonResponse
   {
       try{
           $user = auth()->user();
           $personalBlogs = PersonalBlog::where('user_id', $user->id)->delete();
           return $this->successResponse($personalBlogs,'Your notes has been deleted successfully',200);
       }
       catch (\Exception $exception){
           return $this->errorResponse($exception->getMessage(),400);
       }
   }
   public function show($id): \Illuminate\Http\JsonResponse
   {
       try{
           $user_id = auth()->user()->id;
           $personalBlog = PersonalBlog::findOrFail($id);
           if($personalBlog->user_id != $user_id){
               return $this->errorResponse('Unauthorized',403);
           }
           return $this->successResponse($personalBlog);
       }
       catch (\Exception $exception){
           return $this->errorResponse($exception->getMessage(),400);
       }
   }

}
