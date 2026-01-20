<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\ChatController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard Route (مع الـ Controller)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Student Profile
    Route::get('/student', [StudentController::class, 'index'])->name('student.index');
    Route::post('/student', [StudentController::class, 'store'])->name('student.store');
    Route::patch('/student/{student}', [StudentController::class, 'update'])->name('student.update');

    // Assessments
    Route::get('/assessments', [AssessmentController::class, 'index'])->name('assessments.index');
    Route::get('/assessments/{assessment}', [AssessmentController::class, 'show'])->name('assessments.show');
    Route::post('/assessments/{assessment}/submit', [AssessmentController::class, 'submit'])->name('assessments.submit');
    Route::get('/assessments/{response}/results', [AssessmentController::class, 'results'])->name('assessments.results');

    // Recommendations
    Route::get('/recommendations', [RecommendationController::class, 'index'])->name('recommendations.index');
    Route::post('/recommendations/generate', [RecommendationController::class, 'generate'])->name('recommendations.generate');

    // Chat with AI
    Route::post('/chat/message', [ChatController::class, 'sendMessage'])->name('chat.message');
});

require __DIR__.'/auth.php';
