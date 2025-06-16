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
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseContentController;

use App\Http\Controllers\SolutionController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\CourseSubscriptionController;
use App\Http\Controllers\CourseProgressController;
use App\Http\Controllers\RoadmapController;
use App\Http\Controllers\RoadmapProgressController;
use App\Http\Controllers\RoadmapStepController;
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

Route::prefix('subjects')->group(function() {
    Route::get('subjects', [SubjectController::class, 'apiIndex'])->name('api.subjects.index');
    Route::get('subjects/{subject}', [SubjectController::class, 'apiShow'])->name('api.subjects.show');
    Route::post('subjects', [SubjectController::class, 'apiStore'])->name('api.subjects.store');
    Route::put('subjects/{subject}', [SubjectController::class, 'apiUpdate'])->name('api.subjects.update');
    Route::delete('subjects/{subject}', [SubjectController::class, 'apiDestroy'])->name('api.subjects.destroy');
    Route::get('subjects/search', [SubjectController::class, 'apiSearch'])->name('api.subjects.search');
});



Route::prefix('content-subjects')->name('content_subjects.')->group(function () {
    Route::get('/', [ContentSubjectController::class, 'apiIndex'])->name('index');
    Route::get('search', [ContentSubjectController::class, 'apiSearch'])->name('search');
    Route::get('{id}', [ContentSubjectController::class, 'apiShow'])->name('show');
    Route::post('/', [ContentSubjectController::class, 'apiStore'])->name('store');
    Route::post('{id}', [ContentSubjectController::class, 'apiUpdate'])->name('update');
    Route::delete('{id}', [ContentSubjectController::class, 'apiDestroy'])->name('destroy');
});

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'apiIndex']);
    Route::get('/{id}', [CategoryController::class, 'apiShow']);
    Route::post('/', [CategoryController::class, 'apiStore']);
    Route::put('/{id}', [CategoryController::class, 'apiUpdate']);
    Route::delete('/{id}', [CategoryController::class, 'apiDestroy']);
    Route::get('/search', [CategoryController::class, 'apiSearch']);
});


Route::prefix('courses')->group(function () {
    Route::get('/', [CourseController::class, 'apiIndex']);
    Route::get('/search', [CourseController::class, 'apiFilter']);
    Route::get('/{id}', [CourseController::class, 'apiShow']);
    Route::post('/', [CourseController::class, 'apiStore']);
    Route::post('/{id}', [CourseController::class, 'apiUpdate']);
    Route::delete('/{id}', [CourseController::class, 'apiDestroy']);

    Route::middleware(['auth:sanctum', 'active.subscription'])->group(function () {
        Route::get('/{courseId}/progress', [CourseProgressController::class, 'getCourseProgress']);
        
        Route::post('/{courseId}/recalculate-progress', [CourseProgressController::class, 'recalculateProgress']);
    });
});

Route::prefix('contents')->middleware(['auth:sanctum', 'active.subscription'])->group(function () {
    Route::get('{courseId}', [CourseContentController::class, 'index']);
    Route::get('{courseId}/search', [CourseContentController::class, 'search']);
    Route::post('/', [CourseContentController::class, 'store']);
    Route::post('{content}', [CourseContentController::class, 'update']);
    Route::delete('{content}', [CourseContentController::class, 'destroy']);
    Route::get('show/{content}', [CourseContentController::class, 'show']);
    Route::get('download/video/{content}', [CourseContentController::class, 'downloadVideo']);
    Route::get('download/attachment/{content}', [CourseContentController::class, 'downloadAttachment']);

    Route::post('progress/update', [CourseProgressController::class, 'updateProgress']);
    Route::get('progress/last-position', [CourseProgressController::class, 'getLastPosition']);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/ratings', [RatingController::class, 'store']);
    Route::put('/ratings/{rating}', [RatingController::class, 'update']);
    Route::delete('/ratings/{rating}', [RatingController::class, 'destroy']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/subscriptions', [CourseSubscriptionController::class, 'apiIndex']);
    Route::post('/subscriptions/subscribe', [CourseSubscriptionController::class, 'apiSubscribe']);
    Route::post('/subscriptions/unsubscribe', [CourseSubscriptionController::class, 'apiUnsubscribe']);
    Route::post('/subscriptions/mark-paid', [CourseSubscriptionController::class, 'apiMarkAsPaid']);
});



Route::middleware(['auth:sanctum'])->group(function () {

    // مسارات الـ Roadmap
    Route::prefix('roadmaps')->group(function () {
        // الحصول على جميع الخطط الدراسية
        Route::get('/', [RoadmapController::class, 'index']);

        // عرض خطة دراسية حسب ID
        Route::get('{roadmapId}', [RoadmapController::class, 'show']);

        // إنشاء خطة دراسية جديدة
        Route::post('/', [RoadmapController::class, 'store']);

        // تحديث خطة دراسية
        Route::put('{roadmapId}', [RoadmapController::class, 'update']);

        // حذف خطة دراسية
        Route::delete('{roadmapId}', [RoadmapController::class, 'destroy']);
    });

    // مسارات التقدم في الخطة الدراسية
    Route::prefix('roadmap-progress')->group(function () {
        // عرض التقدم في الخطة الدراسية
        Route::get('{roadmapId}', [RoadmapProgressController::class, 'showProgress']);
    });

    // مسارات الخطوات (Steps) في الخطة الدراسية
    Route::prefix('roadmap-steps')->group(function () {
        // الحصول على جميع الخطوات الخاصة بخطة دراسية معينة
        Route::get('roadmap/{roadmapId}', [RoadmapStepController::class, 'getStepsByRoadmap']);

        // عرض خطوة معينة حسب ID
        Route::get('{stepId}', [RoadmapStepController::class, 'showStep']);

        // إنشاء خطوة جديدة
        Route::post('/', [RoadmapStepController::class, 'store']);

        // تحديث خطوة
        Route::put('{stepId}', [RoadmapStepController::class, 'update']);

        // حذف خطوة
        Route::delete('{stepId}', [RoadmapStepController::class, 'destroy']);

        // ربط كورسات بخطوة
        Route::post('attach-courses/{stepId}', [RoadmapStepController::class, 'attachCourses']);
    });

});
Route::get('/test-stripe-config', function () {
    return response()->json([
        'publishable_key' => config('stripe.publishable_key'),
        'secret_key' => config('stripe.secret_key')
    ]);
});