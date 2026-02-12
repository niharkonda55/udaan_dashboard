<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Cameraman\CameramanDashboardController;
use App\Http\Controllers\Editor\EditorDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard redirect
Route::get('/', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/projects/create', [AdminDashboardController::class, 'createProject'])->name('projects.create');
    Route::post('/projects', [AdminDashboardController::class, 'storeProject'])->name('projects.store');
    // Specific routes must come before parameterized routes to avoid route conflicts
    Route::get('/projects/trash', [AdminDashboardController::class, 'showTrash'])->name('projects.trash.index');
    Route::get('/projects/completed', [AdminDashboardController::class, 'showCompleted'])->name('projects.completed');
    // Parameterized routes come after specific routes
    Route::get('/projects/{project}', [AdminDashboardController::class, 'showProject'])->name('projects.show');
    Route::post('/projects/{project}/assignment', [AdminDashboardController::class, 'updateAssignment'])->name('projects.updateAssignment');
    Route::post('/projects/{project}/deadlines', [AdminDashboardController::class, 'updateDeadlines'])->name('projects.deadlines.update');
    Route::post('/projects/{project}/approve', [AdminDashboardController::class, 'approveProject'])->name('projects.approve');
    Route::post('/projects/{project}/rework', [AdminDashboardController::class, 'sendRework'])->name('projects.rework');
    Route::post('/projects/{project}/complete', [AdminDashboardController::class, 'completeProject'])->name('projects.complete');
    Route::post('/projects/{project}/status', [AdminDashboardController::class, 'changeStatus'])->name('projects.changeStatus');
    Route::post('/projects/{project}/trash', [AdminDashboardController::class, 'trashProject'])->name('projects.trash');
    Route::post('/projects/{id}/restore', [AdminDashboardController::class, 'restoreProject'])->name('projects.restore');
});

// Cameraman routes
Route::middleware(['auth', 'role:cameraman'])->prefix('cameraman')->name('cameraman.')->group(function () {
    Route::get('/dashboard', [CameramanDashboardController::class, 'index'])->name('dashboard');
    // Specific routes must come before parameterized routes to avoid route conflicts
    Route::get('/projects/completed', [CameramanDashboardController::class, 'showCompleted'])->name('projects.completed');
    // Parameterized routes come after specific routes
    Route::get('/projects/{project}', [CameramanDashboardController::class, 'showProject'])->name('projects.show');
    Route::post('/projects/{project}/shooting/start', [CameramanDashboardController::class, 'markShootingStarted'])->name('projects.shooting.start');
    Route::post('/projects/{project}/shooting/complete', [CameramanDashboardController::class, 'markShootingCompleted'])->name('projects.shooting.complete');
    Route::post('/projects/{project}/raw-media', [CameramanDashboardController::class, 'updateRawMedia'])->name('projects.rawMedia');
});

// Editor routes
Route::middleware(['auth', 'role:editor'])->prefix('editor')->name('editor.')->group(function () {
    Route::get('/dashboard', [EditorDashboardController::class, 'index'])->name('dashboard');
    // Specific routes must come before parameterized routes to avoid route conflicts
    Route::get('/projects/completed', [EditorDashboardController::class, 'showCompleted'])->name('projects.completed');
    // Parameterized routes come after specific routes
    Route::get('/projects/{project}', [EditorDashboardController::class, 'showProject'])->name('projects.show');
    Route::post('/projects/{project}/editing/start', [EditorDashboardController::class, 'markEditingStarted'])->name('projects.editing.start');
    Route::post('/projects/{project}/review', [EditorDashboardController::class, 'markReadyForReview'])->name('projects.review');
    Route::post('/projects/{project}/final-delivery', [EditorDashboardController::class, 'updateFinalDelivery'])->name('projects.finalDelivery');
});

