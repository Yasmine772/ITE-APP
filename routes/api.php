<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\verifiedEmail;
use App\Models\User;
use App\Response\Response;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\YearController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\SpecializationController;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// when clicking on verification link
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request)
    {
    $request->fulfill();
    event(new Verified(User::query()->find($request->route('id'))));
    return Response::Success(true, 'Email Verified Successfully');

    })->middleware(['auth:sanctum', 'signed'])->name('verification.verify');

// resend verification email
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return Response::Success(true, 'Verification link sent!');
})->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');


Route::group(['middleware' => ['auth:sanctum', verifiedEmail::class]], function () {
    Route::get('/user', [UserController::class, 'user']);
    Route::get('/logout', [UserController::class, 'logout']);
});

Route::post('signup', [UserController::class, 'register']);
Route::post('signing', [UserController::class, 'login']);


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




