<?php


use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\YearController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\SpecializationController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\SubjectController;

use App\Http\Controllers\ContentSubjectController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
Route::get('logout', [UserController::class, 'logout'])->middleware('auth:sanctum');

Route::apiResource('years', YearController::class);
Route::apiResource('semesters', SemesterController::class);
Route::apiResource('specializations', SpecializationController::class);
Route::post('AddspecializationsToYear/{specializationID}', [SpecializationController::class, 'AddSpecializationToYear']);

//Users:
Route::post('/updateUserProfile', [ProfileController::class, 'updateUserProfile']);
Route::post('/showUserProfile', [ProfileController::class, 'showUserProfile']);

Route::middleware('auth:sanctum')->group(function () {
    //User profile:
    Route::post('/updateUserProfile', [ProfileController::class, 'updateUserProfile']);
    Route::post('/showUserProfile', [profileController::class, 'showUserProfile']);
    //articles:
    Route::post('/addArticle', [ArticleController::class, 'addArticle']);
    Route::post('/editArticles', [ArticleController::class, 'editArticles']);
    Route::post('/deleteArticle', [ArticleController::class, 'deleteArticle']);
    Route::post('/addComplaint', [ComplaintController::class, 'addComplaint']);
    Route::post('/deleteComplaint', [ComplaintController::class, 'deleteComplaint']);
    Route::post('/editComplaint', [ComplaintController::class, 'editComplaint']);

    
});
Route::get('/showArticles', [ArticleController::class, 'showArticles']);
Route::get('/showComplaintes', [ComplaintController::class, 'showComplaintes']);


Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
Route::get('logout', [UserController::class, 'logout'])->middleware('auth:sanctum');


Route::prefix('subjects')->name('subjects.')->group(function () {
    Route::get('/', [SubjectController::class, 'apiIndex'])->name('index');
    Route::get('search', [SubjectController::class, 'apiSearch'])->name('search'); // 👈 الآن فوق
    Route::get('{id}', [SubjectController::class, 'apiShow'])->name('show');
    Route::post('/', [SubjectController::class, 'apiStore'])->name('store');
    Route::put('{id}', [SubjectController::class, 'apiUpdate'])->name('update');
    Route::delete('{id}', [SubjectController::class, 'apiDestroy'])->name('destroy');
});


Route::prefix('content-subjects')->name('content_subjects.')->group(function () {
    Route::get('/', [ContentSubjectController::class, 'apiIndex'])->name('index');
    Route::get('search', [ContentSubjectController::class, 'apiSearch'])->name('search');
    Route::get('{id}', [ContentSubjectController::class, 'apiShow'])->name('show');
    Route::post('/', [ContentSubjectController::class, 'apiStore'])->name('store');
    Route::post('{id}', [ContentSubjectController::class, 'apiUpdate'])->name('update');
    Route::delete('{id}', [ContentSubjectController::class, 'apiDestroy'])->name('destroy');
});