<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\GroupMemberController;
use App\Http\Controllers\Api\SubTaskController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\FileUploadController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\ClassController;
use App\Http\Controllers\Api\LecturerController;
use App\Http\Controllers\Api\DeviceTokenController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::post('forgot-password', [AuthController::class, 'forgot_password']);
Route::post('verify-otp', [AuthController::class, 'verify_otp']);
Route::post('reset-password', [AuthController::class, 'reset_password']);

Route::get('/classes', [ClassController::class, 'index']);
Route::get('/courses', [CourseController::class, 'index']);

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Lecturer
    |--------------------------------------------------------------------------
    */

    Route::prefix('lecturer')->group(function () {
        Route::get('/classes', [LecturerController::class, 'classes']);
        Route::put('/classes', [LecturerController::class, 'updateClasses']);
        Route::get('/classes/{id}/tasks', [LecturerController::class, 'classTasks']);
        Route::get('/tasks/{taskId}/overview', [LecturerController::class, 'taskOverview']);
    });

    /*
    |--------------------------------------------------------------------------
    | Device Tokens
    |--------------------------------------------------------------------------
    */

    Route::post('/device-tokens', [DeviceTokenController::class, 'store']);
    Route::delete('/device-tokens', [DeviceTokenController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | Authenticated User
    |--------------------------------------------------------------------------
    */

    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
    Route::put('me', [AuthController::class, 'updateProfile']);

    Route::post('change-password', [AuthController::class, 'changePassword']);
    Route::post('verify-password', [AuthController::class, 'verifyPassword']);

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('dashboard/{user_id}', [DashboardController::class, 'index']);

    /*
    |--------------------------------------------------------------------------
    | Users
    |--------------------------------------------------------------------------
    */

    Route::get('users', [UserController::class, 'index']);
    Route::get('users/search', [UserController::class, 'search']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);

    Route::get('users/{userId}/abilities', [UserController::class, 'abilities']);
    Route::post('users/{userId}/abilities', [UserController::class, 'storeAbility']);
    Route::delete('profile-abilities/{id}', [UserController::class, 'destroyAbility']);

    Route::get('profile_abilities', [UserController::class, 'getProfileAbilities']);
    Route::put('profile_abilities', [UserController::class, 'updateProfileAbilities']);

    /*
    |--------------------------------------------------------------------------
    | Groups
    |--------------------------------------------------------------------------
    */

    Route::get('/groups/user/{user_id}', [GroupController::class, 'getByUser']);
    Route::get('/groups/{group_id}', [GroupController::class, 'detail']);
    Route::post('/groups', [GroupController::class, 'store']);
    Route::post('/groups/join', [GroupController::class, 'join']);
    Route::delete('/groups/{id}', [GroupController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | Group Members
    |--------------------------------------------------------------------------
    */

    Route::get('groups/{groupId}/members', [GroupMemberController::class, 'index']);
    Route::post('groups/{groupId}/members', [GroupMemberController::class, 'store']);
    Route::put('group-members/{id}', [GroupMemberController::class, 'update']);
    Route::delete('groups/{groupId}/members/{userId}', [GroupMemberController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | Tasks
    |--------------------------------------------------------------------------
    */

    Route::prefix('tasks')->group(function () {

        Route::get('/user/{user_id}', [TaskController::class, 'getByUser']);
        Route::get('/{task_id}/detail/{user_id}', [TaskController::class, 'detail']);

        Route::post('/', [TaskController::class, 'store']);
        Route::put('/{id}', [TaskController::class, 'update']);
        Route::delete('/{id}', [TaskController::class, 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | Subtasks
    |--------------------------------------------------------------------------
    */

    Route::post('/tasks/{task_id}/subtasks', [SubTaskController::class, 'store']);
    Route::patch('subtasks/{subtask_id}/progress', [SubTaskController::class, 'updateProgress']);
    Route::delete('subtasks/{id}', [SubTaskController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | Task Links
    |--------------------------------------------------------------------------
    */

    Route::get('tasks/{taskId}/links', [TaskController::class, 'links']);
    Route::post('tasks/{taskId}/links', [TaskController::class, 'storeLink']);
    Route::delete('tasks/{taskId}/links', [TaskController::class, 'destroyAllLinks']);
    Route::delete('task-links/{id}', [TaskController::class, 'destroyLink']);

    /*
    |--------------------------------------------------------------------------
    | Task Files
    |--------------------------------------------------------------------------
    */

    Route::get('tasks/{taskId}/files', [TaskController::class, 'files']);
    Route::post('tasks/{taskId}/files', [TaskController::class, 'storeFile']);
    Route::delete('tasks/{taskId}/files', [TaskController::class, 'destroyAllFiles']);
    Route::delete('task-files/{id}', [TaskController::class, 'destroyFile']);

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */

    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications', [NotificationController::class, 'store']);
    Route::post('/notifications/delete-multiple', [NotificationController::class, 'destroyMultiple']);
    Route::put('/notifications/mark-read-multiple', [NotificationController::class, 'markMultipleAsRead']);
    Route::put('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
    Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | Uploads
    |--------------------------------------------------------------------------
    */

    Route::post('/upload/avatar', [FileUploadController::class, 'uploadAvatar']);
    Route::post('/upload/task-file', [FileUploadController::class, 'uploadTaskFile']);

});