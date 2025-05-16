<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Response\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function addArticle(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title'   => 'required|string|min:10|max:100',
                'content' => 'required|string|min:50|max:10000|regex:/^[\p{Arabic}a-zA-Z0-9\s.,\-_\!\؟\?]+$/u',
            ]);
            if ($validator->fails()) {
                return Response::Error($validator->errors(), 400);
            }

            $article = Article::create([
                'title' => $request->title,
                'content' => $request->content,
                'user_id' => auth()->user()->id,
            ],200);
            return Response::Success($article,'Article has been added successfully',200);

        } catch (\Exception $e) {
            return Response::Error(null, 'Something went wrong: ' . $e->getMessage(), 500);
        }
    }
//************************************************************************************************ */
    public function deleteArticle(Request $request)
    {
        try {
            $article = Article::find($request->article_id);
            if(auth()->user()->id == $article->user_id){
                return Response::Success($article->delete(),'Article has been deleted successfully', 200);
            }
            return Response::Error('You can not delete this', 500);

        } catch (\Exception $e) {
            return Response::Error(null, 'Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //************************************************************************************************* */
    public function showArticles()
    {
        try {
            return Response::Success(Article::all(),'All Articles', 200);
        } catch (\Exception $e) {
            return Response::Error(null, 'Something went wrong: ' . $e->getMessage(), 500);
        }
    }
    //************************************************************************************************* */
    public function editArticles(Request $request)
    {
        try {
            $article = Article::find($request->article_id);

            if($article->is_accepted == 1){
                $validator = Validator::make($request->all(), [
                    'title'   => 'nullable|string|min:10|max:100',
                    'content' => 'nullable|string|min:50|max:10000|regex:/^[\p{Arabic}a-zA-Z0-9\s.,\-_\!\؟\?]+$/u',
                ]);
                if ($validator->fails()) {
                    return Response::Error($validator->errors(), 400);
                }
                $article->title = $request->title ?? $article->title;
                $article->content = $request->content ?? $article->content;
                $article->user_id = auth()->user()->id;
                $article->save();
                return Response::Success($article, 'Article has been updated successfully', 200);
            }
            return Response::Success('we send an order to the admin for edit your article', 200);

        } catch (\Exception $e) {
            return Response::Error(null, 'Something went wrong: ' . $e->getMessage(), 500);
        }
    }
}
