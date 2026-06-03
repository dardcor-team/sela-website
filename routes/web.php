<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/maintenance', function () {
    return view('maintenance');
})->name('maintenance');

Route::get('/coming-soon', function () {
    return view('maintenance');
})->name('coming-soon');

// Super Admin Authentication
Route::get('/admin/login', [AdminDashboardController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminDashboardController::class, 'login']);

// Super Admin Protected Routes
Route::middleware('admin.auth')->prefix('admin')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.overview');
    Route::post('/logout', [AdminDashboardController::class, 'logout'])->name('admin.logout');
    
    // User Management
    Route::get('/users', [AdminDashboardController::class, 'users'])->name('admin.users');
    Route::post('/users/{id}/update-role', [AdminDashboardController::class, 'updateUserRole'])->name('admin.users.update-role');
    Route::post('/users/{id}/delete', [AdminDashboardController::class, 'deleteUser'])->name('admin.users.delete');
    
    // Group & Task Monitoring
    Route::get('/groups', [AdminDashboardController::class, 'groups'])->name('admin.groups');
    Route::get('/groups/{id}', [AdminDashboardController::class, 'groupDetail'])->name('admin.groups.detail');
    
    // Academic Data (Classes & Courses)
    Route::get('/academic', [AdminDashboardController::class, 'academic'])->name('admin.academic');
    Route::post('/academic/classes', [AdminDashboardController::class, 'storeClass'])->name('admin.classes.store');
    Route::post('/academic/classes/{id}/update', [AdminDashboardController::class, 'updateClass'])->name('admin.classes.update');
    Route::post('/academic/classes/{id}/delete', [AdminDashboardController::class, 'deleteClass'])->name('admin.classes.delete');
    Route::post('/academic/courses', [AdminDashboardController::class, 'storeCourse'])->name('admin.courses.store');
    Route::post('/academic/courses/{id}/update', [AdminDashboardController::class, 'updateCourse'])->name('admin.courses.update');
    Route::post('/academic/courses/{id}/delete', [AdminDashboardController::class, 'deleteCourse'])->name('admin.courses.delete');

    // System Health & Maintenance Control
    Route::get('/system', [AdminDashboardController::class, 'system'])->name('admin.system');
    Route::post('/system/toggle-maintenance', [AdminDashboardController::class, 'toggleMaintenance'])->name('admin.system.toggle-maintenance');
});
