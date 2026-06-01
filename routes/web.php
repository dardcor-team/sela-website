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
    
    // System Health & Maintenance Control
    Route::get('/system', [AdminDashboardController::class, 'system'])->name('admin.system');
    Route::post('/system/toggle-maintenance', [AdminDashboardController::class, 'toggleMaintenance'])->name('admin.system.toggle-maintenance');
});
