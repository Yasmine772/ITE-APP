<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\RulesForArticles;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class RulesForArticlesController extends Controller
{
    use ApiResponseTrait;

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
    public function articleDetailsForAdmin(Request $request)
    {
        try {
            $article = Article::find($request->article_id);
            return view('Articles.showArticle', compact('article'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
 //************************************************************************************************** */
    public function showRejectionReason()
    {  
        $rules = RulesForArticles::all();
        return  $rules;
        //return view('Articles.showArticle', compact('rules'));
    }
//************************************************************************************************** */
    public function acceptOrRejectArticle(Request $request)
    {
        try {
            $rejection_reason = [];
            $article = Article::find($request->article_id);
           
            $rejection_reason = $request->input('rejection_reason', []);
            //if admin does not select any reject reason --> article accepted  
            if(empty($rejection_reason)){
                $article->status = 'Accept';
                $article->reasonsOfReject = null;
                $article->save();


                //notification


                return $this->successResponse('Accepted successfully', 200);
                //return redirect()->back()->with('Accepted successfully');
            }
            //if admin select any reject reason --> article rejected and there are reasons  
            $article->reasonsOfReject = json_encode($rejection_reason);
            $article->status = 'Reject';
            $article->save();


            //notification
            
            
            return $this->successResponse('Rejection successfully..The rejection of reasons are ' . implode(', ', $rejection_reason), 200);

            //return redirect()->back()->with('Rejection has been done successfully and we send the reasons to the user');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Something went wrong: ' . $e->getMessage(), 500);
            // return $this->errorResponse('Something went wrong: ' . $e->getMessage(), 500);
        }
    }
}
