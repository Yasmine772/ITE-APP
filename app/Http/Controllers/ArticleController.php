<?php

namespace App\Http\Controllers;

use App\Models\Article;
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
    public function addArticle(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|min:10|max:100',
                'content' => 'required|string|min:50|max:10000|regex:/^[\p{Arabic}a-zA-Z0-9\s.,\-_:;()@!?؟\n؛]+$/u',
            ]);
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 400);
            }
            Article::create([
                'title' => $request->title,
                'content' => $request->content,
                'user_id' => auth()->user()->id,
                'user_details' => json_encode(auth()->user()->only(['name', 'profile_photo_path'])),
            ]);

            $user = auth()->user();
            $this->notificationService->sendToUserForArticle($user, 'Hi' . $user->name, 'We have sent your article to the admin for review.');
            return $this->successResponse([
                'message' => 'Article added successfully'
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse(null, 'Something went wrong: ' . $e->getMessage(), 500);
        }

    }
    //************************************************************************************************ */
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
    //************************************************************************************************* */
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

            //notification to user that.. we send request to the admin for edit your article.
            $user = auth()->user();
            $this->notificationService->sendToUserForEditArticle($user, 'Hi' . $user->name, 'Your update has been sent to the admin for verification.');
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
//************************************************************************************************** */
    public function articleDetails(Request $request)
    {
        try {
            return $this->successResponse(Article::find($request->article_id), 'Article details:', 200);
        }catch (\Exception $e) {
            return $this->errorResponse('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
//************************************************************************************************** */
    public function showPendingArticleforAdmin()
    {
        try {
            $articles = Article::where('status', 'Pending')->get();

            if ($articles->isEmpty()) {
                //return response()->json(['data' => 'There are not articles not acceppted!']);
                return redirect()->back()->withErrors('No pending articles! ', 500);
            }
            //return $this->successResponse($articles, 'All pending articles', 200);
            return view('Articles.PendingArticles', compact('articles'));
        } catch (\Exception $e) {
            //  return redirect()->back()->withErrors('Something went wrong: ' . $e->getMessage(), 500);
            return $this->errorResponse('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
//************************************************************************************************** */
    public function acceptArticle(Request $request)
    {
        try {
            $article = Article::find($request->article_id);
            $article->status = 'Accept' ;
            $article->save();

            //notification to user for accept his article

           // return $this->successResponse('successfull', 200);
            return redirect()->back()->with('successfull');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Something went wrong: ' . $e->getMessage(), 500);
           // return $this->errorResponse('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
//////////////////////////////////////////////////////////////
    public function RejectArticle(Request $request)
    {
        try {
            $article = Article::find($request->article_id);
            $article->status = 'Reject';
            $validator = Validator::make($request->all(), [
                'reasonsOfReject' => 'required|string|min:10|max:1000',
            ]);
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 400);
            }
            $article->reasonsOfReject = $request->reasonsOfReject;
            $article->save();

            //notification to user that.. your article has been rejected for this reasons (reasonsOfReject)

            // return $this->successResponse('successfull', 200);
            return redirect()->back()->with('successfuly');
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //************************************************************************************************** */
}
