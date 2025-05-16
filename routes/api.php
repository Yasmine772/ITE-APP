<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\YearController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\SpecializationController;
use App\Http\Controllers\profileController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('register',[UserController::class,'register']);
Route::post('login',[UserController::class,'login']);
Route::get('logout',[UserController::class,'logout'])->middleware('auth:sanctum');

Route::apiResource('years', YearController::class);
Route::apiResource('semesters', SemesterController::class);
Route::apiResource('specializations', SpecializationController::class);
Route::post('AddspecializationsToYear/{specializationID}', [SpecializationController::class, 'AddSpecializationToYear']);


Route::middleware('auth:sanctum')->group(function () {
    //User profile:
    Route::post('/updateUserProfile', [profileController::class, 'updateUserProfile']);
    Route::post('/showUserProfile', [profileController::class, 'showUserProfile']);
    //articles:
    Route::post('/addArticle', [ArticleController::class, 'addArticle']);
    Route::post('/editArticles', [ArticleController::class, 'editArticles']);
    Route::post('/deleteArticle', [ArticleController::class, 'deleteArticle']);
});
Route::get('/showArticles', [ArticleController::class, 'showArticles']);

