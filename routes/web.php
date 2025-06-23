<?php

use App\Http\Controllers\AdviceController;
use App\Http\Controllers\AssignmentController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\SubjectController;

use App\Http\Controllers\ContentSubjectController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseContentController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SolutionController;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Http\Controllers\CourseSubscriptionController;
use App\Http\Controllers\RoadmapController;
use App\Http\Controllers\RoadmapProgressController;
use App\Http\Controllers\YearController;
use App\Http\Controllers\SpecializationController;
use App\Http\Controllers\SemesterController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});


Route::get('years-view', [YearController::class, 'indexView'])->name('years.index');
Route::get('years-view/create', [YearController::class, 'create'])->name('years.create');
Route::post('years-view', [YearController::class, 'storeView'])->name('years.store');
Route::get('years-view/{year}', [YearController::class, 'showView'])->name('years.show');
Route::get('years-view/{year}/edit', [YearController::class, 'edit'])->name('years.edit');
Route::put('years-view/{year}', [YearController::class, 'updateView'])->name('years.update');
Route::delete('years-view/{year}', [YearController::class, 'destroyView'])->name('years.destroy');


Route::prefix('semesters')->group(function () {
    Route::get('/', [SemesterController::class, 'indexView'])->name('semesters.index');
    Route::get('/create', [SemesterController::class, 'createView'])->name('semesters.create');
    Route::post('/', [SemesterController::class, 'storeView'])->name('semesters.store');
    Route::get('/{semester}/edit', [SemesterController::class, 'editView'])->name('semesters.edit');
    Route::put('/{semester}', [SemesterController::class, 'updateView'])->name('semesters.update');
    Route::delete('/{semester}', [SemesterController::class, 'destroyView'])->name('semesters.destroy');
});

Route::prefix('specializations')->group(function () {
    Route::get('/', [SpecializationController::class, 'bladeIndex'])->name('specializations.index');
    Route::get('/create', [SpecializationController::class, 'bladeCreate'])->name('specializations.create');
    Route::post('/', [SpecializationController::class, 'bladeStore'])->name('specializations.store');
    Route::get('/{specialization}/edit', [SpecializationController::class, 'bladeEdit'])->name('specializations.edit');
    Route::put('/{specialization}', [SpecializationController::class, 'bladeUpdate'])->name('specializations.update');
    Route::delete('/{specialization}', [SpecializationController::class, 'bladeDestroy'])->name('specializations.destroy');
});
Route::prefix('subjects')->name('subjects.')->group(function() {
    Route::get('/', [SubjectController::class, 'index'])->name('index');
    Route::get('create', [SubjectController::class, 'create'])->name('create');
    Route::post('store', [SubjectController::class, 'store'])->name('store');
    Route::get('edit/{id}', [SubjectController::class, 'edit'])->name('edit');
    Route::put('update/{id}', [SubjectController::class, 'update'])->name('update');
    Route::delete('destroy/{id}', [SubjectController::class, 'destroy'])->name('destroy');
    Route::get('search', [SubjectController::class, 'search'])->name('search');
});


Route::prefix('content-subjects')->name('content_subjects.')->group(function() {
    Route::get('/', [ContentSubjectController::class, 'index'])->name('index');
    Route::get('create', [ContentSubjectController::class, 'create'])->name('create');
    Route::post('store', [ContentSubjectController::class, 'store'])->name('store');
    Route::get('edit/{id}', [ContentSubjectController::class, 'edit'])->name('edit');
    Route::put('update/{id}', [ContentSubjectController::class, 'update'])->name('update');
    Route::delete('destroy/{id}', [ContentSubjectController::class, 'destroy'])->name('destroy');
    Route::get('search', [ContentSubjectController::class, 'search'])->name('search');
});

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});

Route::prefix('courses')->group(function () {
    Route::get('/', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/{id}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/{id}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/{id}', [CourseController::class, 'destroy'])->name('courses.destroy');

    Route::get('/search', [CourseController::class, 'filter'])->name('courses.search');
});

Route::prefix('course_contents')->group(function () {
    Route::get('web/{courseId}', [CourseContentController::class, 'webIndex'])->name('course_contents.webIndex');
    Route::get('web/{courseId}/search', [CourseContentController::class, 'webSearch'])->name('course_contents.webSearch');
    Route::get('web/create/{courseId}', [CourseContentController::class, 'webCreate'])->name('course_contents.webCreate');
    Route::post('web/store', [CourseContentController::class, 'webStore'])->name('course_contents.webStore');
    Route::get('web/edit/{content}', [CourseContentController::class, 'webEdit'])->name('course_contents.webEdit');
    Route::post('web/update/{content}', [CourseContentController::class, 'webUpdate'])->name('course_contents.webUpdate');
    Route::delete('web/delete/{content}', [CourseContentController::class, 'webDestroy'])->name('course_contents.webDestroy');
    Route::get('web/show/{content}', [CourseContentController::class, 'webShow'])->name('course_contents.webShow');
});

Route::group(['middleware' => ['auth:sanctum', 'Teacher']], function () {
    //advices:
    Route::post('/addAdvices', [AdviceController::class, 'addAdvice'])->name('advices.addAdvice');
    Route::post('/deleteAdvice', [AdviceController::class, 'deleteAdvice'])->name('advices.deleteAdvice');
    Route::post('/editAdvices', [AdviceController::class, 'editAdvices'])->name('advices.editAdvices');

    //assignments:
    Route::post('/addAssignment', [AssignmentController::class, 'addAssignment'])->name('assignments.addAssignment');
    Route::post('/deleteAssignment', [AssignmentController::class, 'deleteAssignment'])->name('assignments.deleteAssignment');
    Route::post('/editAssignment', [AssignmentController::class, 'editAssignment'])->name('assignments.editAssignment');

    //Solutions of assignments:
    Route::post('/addSolution', [SolutionController::class, 'addSolution'])->name('solutions.addSolution');
    Route::post('/deleteSolution', [SolutionController::class, 'deleteSolution'])->name('solutions.deleteSolution');
    Route::post('/editSolution', [SolutionController::class, 'editSolution'])->name('solutions.editSolution');

    //Exams:
    Route::post('/addExam', [ExamController::class, 'addExam'])->name('Exams.addExam');
    Route::post('/deleteExam', [ExamController::class, 'deleteExam'])->name('Exams.deleteExam');
    Route::post('/editExam', [ExamController::class, 'editExam'])->name('Exams.editExam');

    //Questions:
    Route::post('/addQuestion', [QuestionController::class, 'addQuestion'])->name('Exams.addQuestion');
    Route::post('/deleteQuestion', [QuestionController::class, 'deleteQuestion'])->name('Exams.deleteQuestion');
    Route::post('/editQuestion', [QuestionController::class, 'editQuestion'])->name('Exams.editQuestion');

    //Options:
    Route::post('/addOption', [OptionController::class, 'addOption'])->name('Exams.addOption');
    Route::post('/deleteOption', [OptionController::class, 'deleteOption'])->name('Exams.deleteOption');
    Route::post('/editOption', [OptionController::class, 'editOption'])->name('Exams.editOption');

    //show exam
    Route::post('/showExamForTeacher', [ExamController::class, 'showExam'])->name('Exam.showExam'); //web+api


});
Route::post('/showAdvices', [AdviceController::class, 'showAdvices'])->name('advices.All_Advices');
Route::post('/showSolutions', [SolutionController::class, 'showSolutions'])->name('solutions.All_solutions');
Route::post('/showAssignment', [AssignmentController::class, 'showAssignment'])->name('assignments.All_assignments');


Route::get('/test-stripe-config', function () {

    dd(config('stripe.publishable_key'), config('stripe.secret_key'));
});