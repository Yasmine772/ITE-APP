<?php

use App\Http\Controllers\AdviceController;
use App\Http\Controllers\AdvicesController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\YearController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\SpecializationController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\SubjectController;

use App\Http\Controllers\ContentSubjectController;
use App\Http\Controllers\SolutionController;

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


Route::middleware('auth:sanctum')->group(function () {
    //User profile:
    Route::post('/updateUserProfile', [ProfileController::class, 'updateUserProfile']);
    Route::post('/showUserProfile', [profileController::class, 'showUserProfile']);
    //articles:
    Route::post('/addArticle', [ArticleController::class, 'addArticle']);
    Route::post('/editArticles', [ArticleController::class, 'editArticles']);
    Route::post('/deleteArticle', [ArticleController::class, 'deleteArticle']);

    //complaints:
    Route::post('/addComplaint', [ComplaintController::class, 'addComplaint']);
    Route::post('/deleteComplaint', [ComplaintController::class, 'deleteComplaint']);
    Route::post('/editComplaint', [ComplaintController::class, 'editComplaint']);

    // //advices:
    // Route::post('/addAdvice', [AdviceController::class, 'addAdvice']);
    // Route::post('/deleteAdvice', [AdviceController::class, 'deleteAdvice']);
    // Route::post('/editAdvices', [AdviceController::class, 'editAdvices']);

    // //assignments:
    // Route::post('/addAssignment', [AssignmentController::class, 'addAssignment']);
    // Route::post('/deleteAssignment', [AssignmentController::class, 'deleteAssignment']);
    // Route::post('/editAssignment', [AssignmentController::class, 'editAssignment']);

    // //Solutions of assignments:
    // Route::post('/addSolution', [SolutionController::class, 'addSolution']);
    // Route::post('/deleteSolution', [SolutionController::class, 'deleteSolution']);
    // Route::post('/editSolution', [SolutionController::class, 'editSolution']);
});
Route::get('/showArticles', [ArticleController::class, 'showArticles']);
Route::get('/showComplaintes', [ComplaintController::class, 'showComplaintes']);
Route::post('/displayAdvices', [AdviceController::class, 'displayAdvices']);
Route::post('/displayAssignment', [AssignmentController::class, 'displayAssignment']);
Route::post('/displaySolutions', [SolutionController::class, 'displaySolutions']);
Route::post('/articleDetails', [ArticleController::class, 'articleDetails']);
Route::post('/complaintDetails', [ComplaintController::class, 'complaintDetails']);









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