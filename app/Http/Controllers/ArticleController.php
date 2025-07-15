<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use App\Services\NotificationService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    use ApiResponseTrait;
    protected NotificationService $notificationService;
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    public function addArticle(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|min:10|max:100',
                'content' => 'required|string|min:50|max:10000|regex:/^[\p{Arabic}a-zA-Z0-9\s.,\-_:;()@!?؟\n؛]+$/u',
            ]);
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 400);
            }

            $article = Article::create([
                'title' => $request->title,
                'content' => $request->content,
                'user_id' => auth()->user()->id,
                'user_details' => json_encode(auth()->user()->only(['name', 'profile_photo_path'])),
            ]);
            $studentInfo = auth()->user();
            //Notification for admin
            $admin = User::where('name', 'admin')->get();
            $this->notificationService->sendToAdmin($admin, 'New Article', 'You have an article to verify for publication',$article,$studentInfo);

            $user = auth()->user();
            $this->notificationService->sendToUserForArticle($user, 'Hi' . $user->name, 'We have sent your article to the admin for review.');
            return $this->successResponse($article, 'We have sent your article to the admin for review.', 200);
        } catch (\Exception $e) {
            return $this->errorResponse(null, 'Something went wrong: ' . $e->getMessage(), 500);
        }
    }
//************************************************************************************************ */
    public function editArticles(Request $request)
    {
        try {
            $article = Article::find($request->article_id);
            $validator = Validator::make($request->all(), [
                'title'   => 'nullable|string|min:10|max:100',
                'content' => 'nullable|string|min:50|max:10000|regex:/^[\p{Arabic}a-zA-Z0-9\s.,\-_:;()@!?؟\n؛]+$/u',
            ]);
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 400);
            }
            $article->title = $request->title ?? $article->title;
            $article->content = $request->content ?? $article->content;
            $article->status = 'Pending';
            $article->user_id = auth()->user()->id;
            $article->user_details =  json_encode(auth()->user()->only(['name', 'profile_photo_path']));
            $article->save();
            //Notification for admin
            $studentInfo = auth()->user();
            $admin = User::role('admin')->get();
            $this->notificationService->sendToAdmin($admin, 'Article updated', 'An article was edited after publishing , check it for republishing',$article,$studentInfo);
            //Notification for student
            $user = auth()->user();
            $this->notificationService->sendToUserForEditArticle($user, 'Hi' . $user->name, 'Your update has been sent to the admin for verification.');
            $this->notificationService->sendFCMNotification($user,'Hi' . $user->name, 'Your update has been sent to the admin for verification.');
            return $this->successResponse($article, 'Your update has been sent to the admin for verification.', 200);

            // return $this->successResponse(null, 'Your Article has been sent to the admin for verification', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
//************************************************************************************************* */
    public function deleteArticle(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $article = Article::find($request->article_id);
            if (auth()->user()->id == $article->user_id) {
                return $this->successResponse($article->delete(), 'Article has been deleted successfully', 200);
            }
            return $this->errorResponse('You can not delete this', 500);
        } catch (\Exception $e) {
            return $this->errorResponse(null, 'Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //************************************************************************************************* */
    public function showAllArticles(): \Illuminate\Http\JsonResponse
    {
        try {
            $articles = Article::where('status','Accept')->get();
            if($articles->isEmpty()){
                return $this->errorResponse('No articles yet!', 500);
            }
            return $this->successResponse($articles, 'All Articles', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
//************************************************************************************************** */
    public function articleDetails(Request $request)
    {
        try {
            return $this->successResponse(Article::find($request->article_id), 'Article details:', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    ////////////////////////////////////////////////////////////
    public function showPendingArticles(): \Illuminate\Http\JsonResponse
    {
        try {
            $articles = Article::where('status','Pending')->get();
            if ($articles->isEmpty()) {
                return $this->errorResponse('No pending articles yet!', 500);
            }
            return $this->successResponse($articles, 'Pending articles', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    /////////////////////////////////////////////////////////////
    public function showRejectArticles(): \Illuminate\Http\JsonResponse
    {
        try {
            $articles = Article::where('status','Reject')->get();
            if ($articles->isEmpty()) {
                return $this->errorResponse('No reject articles yet!', 500);
            }
            return $this->successResponse($articles, 'Reject articles', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    ////////////////////////////////////////////////////////////
    public function showAcceptArticles(): \Illuminate\Http\JsonResponse
    {
        try {
            $articles = Article::where('status', 'Accept')->get();
            if ($articles->isEmpty()) {
                return $this->errorResponse('No accept articles yet!', 500);
            }
            return $this->successResponse($articles, 'Accept articles', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
}
