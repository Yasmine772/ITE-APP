<?php

use App\Http\Controllers\AdvicesController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\MyResourceListController;
use App\Http\Controllers\PersonalBlogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\AdviceController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
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
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ContentSubjectController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseContentController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\MarkController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\QuestionController;
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

Route::post('signup', [UserController::class, 'register']);
Route::post('signin', [UserController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum', verifiedEmail::class]], function () {
    Route::get('/user', [UserController::class, 'user']);
    Route::get('/logout', [UserController::class, 'logout']);
});
// when clicking on verification link
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    event(new Verified(User::query()->find($request->route('id'))));
    return $this->successResponse(true, 'Email Verified Successfully');
})->middleware(['auth:sanctum', 'signed'])->name('verification.verify');

// resend verification email
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return $this->successResponse(true, 'Verification link sent!');
})->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');



//To send  password reset links
Route::post('user/password/email', [UserController::class, 'UserForgetPassword'])->middleware('guest');
Route::post('user/password/code/check', [UserController::class, 'userCheckCode'])->middleware('guest');
Route::post('user/password/reset', [UserController::class, 'UserResetPassword'])->middleware('guest');

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
    Route::get('/showPendingArticles', [ArticleController::class, 'showPendingArticles']);
    Route::get('/showRejectArticles', [ArticleController::class, 'showRejectArticles']);
    Route::get('/showAcceptArticles', [ArticleController::class, 'showAcceptArticles']);

    //complaints:
    Route::post('/addComplaint', [ComplaintController::class, 'addComplaint']);
    Route::post('/deleteComplaint', [ComplaintController::class, 'deleteComplaint']);
    Route::post('/editComplaint', [ComplaintController::class, 'editComplaint']);
    Route::post('/showComplaintes', [ComplaintController::class, 'showComplaintes']);


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

    // //Exams:
    // Route::post('/addExam', [ExamController::class, 'addExam']);
    // Route::post('/deleteExam', [ExamController::class, 'deleteExam']);
    // Route::post('/editExam', [ExamController::class, 'editExam']);

    // //Questions:
    // Route::post('/addQuestion', [QuestionController::class, 'addQuestion']);
    // Route::post('/deleteQuestion', [QuestionController::class, 'deleteQuestion']);
    // Route::post('/editQuestion', [QuestionController::class, 'editQuestion']);

    // //Options:
    // Route::post('/addOption', [OptionController::class, 'addOption']);
    // Route::post('/deleteOption', [OptionController::class, 'deleteOption']);
    // Route::post('/editOption', [OptionController::class, 'editOption']);

    //Answer:
    Route::post('/addAnswer', [AnswerController::class, 'addAnswer']);
    //status of student exam :
    Route::post('/startExam', [MarkController::class, 'startExam']);
    Route::post('/finishExam', [MarkController::class, 'finishExam']);
});

Route::get('/showAllArticles', [ArticleController::class, 'showAllArticles']);

// Route::post('/articleDetails', [ArticleController::class, 'articleDetails']);
// Route::post('/acceptEditArticle', [ArticleController::class, 'acceptEditArticle']);
// Route::get('/showNoneAcceptArticle', [ArticleController::class, 'showNoneAcceptArticle']);

// Route::get('/showPendingArticleforAdmin', [ArticleController::class, 'showPendingArticleforAdmin']);
// Route::post('/RejectArticle', [ArticleController::class, 'RejectArticle']);
// Route::post('/acceptArticle', [ArticleController::class, 'acceptArticle']);
Route::post('/displayAdvices', [AdviceController::class, 'displayAdvices']);

Route::post('/displayAssignment', [AssignmentController::class, 'displayAssignment']);
Route::post('/displayAssignmentdetails', [AssignmentController::class, 'displayAssignmentdetails']);

Route::post('/displaySolutions', [SolutionController::class, 'displaySolutions']);
Route::post('/displaySolutionsdetails', [SolutionController::class, 'displaySolutionsdetails']);

Route::post('/downloadFiles', [AssignmentController::class, 'downloadFiles']);

Route::post('/complaintDetails', [ComplaintController::class, 'complaintDetails']);

Route::post('/showExamForStudent', [ExamController::class, 'showExamForStudent']);
Route::post('/detailsOfExam', [ExamController::class, 'detailsOfExam']);


//Notifications
Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'notifications'], function () {
    Route::get('/show', [NotificationController::class, 'index']);
    Route::post('/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/readAll', [NotificationController::class, 'markAllAsRead']);
    Route::delete('/{id}/destroy', [NotificationController::class, 'destroy']);
    Route::delete('/destroyAll', [NotificationController::class, 'destroyAll']);
    Route::get('/unread/count', [NotificationController::class, 'countUnreadNotifications']);
    Route::get('/unread', [NotificationController::class, 'unreadNotifications']);
});
Route::group(['middleware' => 'auth:sanctum', 'checkUser'], function () {
    Route::post('notifications/send', [NotificationController::class, 'send']);
});
//Advertisements
Route::group(['middleware' => 'auth:sanctum', 'checkUser'], function () {
    Route::get('advertisement/index', [AdvertisementController::class, 'index']);
    Route::post('advertisement/store', [AdvertisementController::class, 'store']);
    Route::put('advertisement/{id}/update', [AdvertisementController::class, 'update']);
    Route::delete('advertisement/{id}/destroy', [AdvertisementController::class, 'destroy']);
    Route::delete('advertisement/destroyAll', [AdvertisementController::class, 'destroyAll']);
});

Route::group(['middleware' => ['auth:sanctum', 'admin']], function () {
    Route::get('advertisement/showAll', [AdvertisementController::class, 'showAll']);
    Route::delete('advertisement/{id}/destroyAdmin', [AdvertisementController::class, 'destroyAdmin']);
    Route::delete('advertisement/destroyAllAdmin', [AdvertisementController::class, 'destroyAllAdmin']);
    //Resources
    Route::get('resource/showAll', [ResourceController::class, 'showAll']);


});
//Resources
Route::group(['middleware' => ['auth:sanctum', 'teacher']], function () {
    Route::get('resource/index', [ResourceController::class, 'index']);
    Route::post('resource/store', [ResourceController::class, 'store']);
    Route::post('resource/{id}/update', [ResourceController::class, 'update']);
    Route::delete('resource/{id}/destroy', [ResourceController::class, 'destroy']);
    Route::delete('resource/destroyAll', [ResourceController::class, 'destroyAll']);
});

// My Resources list
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('myresource/index', [MyResourceListController::class, 'index']);
    Route::get('myresource/store', [MyResourceListController::class, 'store']);
    Route::delete('myresource/{id}/remove', [MyResourceListController::class, 'remove']);

});
//Personal Blog
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('mynotes/index', [PersonalBlogController::class, 'index']);
    Route::get('mynotes/{id}/show', [PersonalBlogController::class, 'show']);
    Route::get('mynotes/store', [PersonalBlogController::class, 'store']);
    Route::post('mynotes/{id}/update', [PersonalBlogController::class, 'update']);
    Route::delete('mynotes/{id}/destroy', [PersonalBlogController::class, 'destroy']);
    Route::delete('mynotes/destroyAll', [PersonalBlogController::class, 'destroyAll']);

});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('myresources/showAll', [MyResourceListController::class, 'show']);
    Route::get('myresources/{id}/add', [MyResourceListController::class, 'store']);

});



Route::prefix('subjects')->name('subjects.')->group(function () {
    Route::get('/', [SubjectController::class, 'apiIndex'])->name('index');
    Route::get('search', [SubjectController::class, 'apiSearch'])->name('search');
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


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/subscriptions', [CourseSubscriptionController::class, 'apiIndex']);
    Route::post('/subscriptions/subscribe', [CourseSubscriptionController::class, 'apiSubscribe']);
    Route::post('/subscriptions/unsubscribe', [CourseSubscriptionController::class, 'apiUnsubscribe']);
    Route::post('/subscriptions/mark-paid', [CourseSubscriptionController::class, 'apiMarkAsPaid']);
});

Route::post('contents', [CourseContentController::class, 'store']);
Route::post('contents/{content}', [CourseContentController::class, 'update']);
Route::delete('contents/{content}', [CourseContentController::class, 'destroy']);
Route::get('contents/{courseId}', [CourseContentController::class, 'index']);

Route::prefix('contents')->middleware(['auth:sanctum', 'active.subscription'])->group(function () {
    //  Route::get('{courseId}', [CourseContentController::class, 'index']);
    Route::get('{courseId}/search', [CourseContentController::class, 'search']);


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

    Route::prefix('roadmaps')->group(function () {
        Route::get('/', [RoadmapController::class, 'index']);

        Route::get('{roadmapId}', [RoadmapController::class, 'show']);

        Route::post('/', [RoadmapController::class, 'store']);

        Route::put('{roadmapId}', [RoadmapController::class, 'update']);

        Route::delete('{roadmapId}', [RoadmapController::class, 'destroy']);
    });

    Route::prefix('roadmap-progress')->group(function () {
        Route::get('{roadmapId}', [RoadmapProgressController::class, 'showProgress']);
    });

    Route::prefix('roadmap-steps')->group(function () {
        Route::get('roadmap/{roadmapId}', [RoadmapStepController::class, 'getStepsByRoadmap']);

        Route::get('{stepId}', [RoadmapStepController::class, 'showStep']);

        Route::post('/', [RoadmapStepController::class, 'store']);

        Route::put('{stepId}', [RoadmapStepController::class, 'update']);

        Route::delete('{stepId}', [RoadmapStepController::class, 'destroy']);

        Route::post('attach-courses/{stepId}', [RoadmapStepController::class, 'attachCourses']);
    });
});
