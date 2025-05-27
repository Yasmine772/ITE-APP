<?php

use App\Http\Controllers\AdviceController;
use App\Http\Controllers\AssignmentController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\SubjectController;

use App\Http\Controllers\ContentSubjectController;
use App\Http\Controllers\SolutionController;

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

Route::middleware('auth:sanctum')->group(function (){
    //advices:
    Route::post('/addAdvices', [AdviceController::class, 'addAdvice']);
    Route::post('/deleteAdvice', [AdviceController::class, 'deleteAdvice']);
    Route::post('/editAdvices', [AdviceController::class, 'editAdvices']);

    //assignments:
    Route::post('/addAssignment', [AssignmentController::class, 'addAssignment']);
    Route::post('/deleteAssignment', [AssignmentController::class, 'deleteAssignment']);
    Route::post('/editAssignment', [AssignmentController::class, 'editAssignment']);

    //Solutions of assignments:
    Route::post('/addSolution', [SolutionController::class, 'addSolution']);
    Route::post('/deleteSolution', [SolutionController::class, 'deleteSolution']);
    Route::post('/editSolution', [SolutionController::class, 'editSolution']);

});
Route::post('/showAdvices', [AdviceController::class, 'showAdvices'])->name('advices.All_Advices');
Route::post('/showSolutions', [SolutionController::class, 'showSolutions'])->name('solutions.All_solutions');
Route::post('/showAssignment', [AssignmentController::class, 'showAssignment'])->name('assignments.All_assignments');
