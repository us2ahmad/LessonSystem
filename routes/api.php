<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\PostStatusController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Student\StudentAuthController;
use App\Http\Controllers\Teacher\TeacherAuthController;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('unauthorized', function () {
    return response()->json(
        ['message' => 'Unauthorized'],
        401
    );
})->name('login');


Route::controller(AdminAuthController::class)
    ->prefix('auth/admin')
    ->name('admin.')
    ->group(function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
        Route::post('/logout', 'logout');
        Route::post('/refresh', 'refresh');
        Route::get('/admin-profile', 'userProfile');
    });

Route::controller(PostStatusController::class)->group(function () {

    Route::post('status', 'changeStatus');
});

//Admin  Route Of Notification 
Route::controller(AdminNotificationController::class)
    ->prefix('notification')->group(function () {
        Route::get('getAll', 'index');
        Route::get('unread', 'unread');
        Route::post('makeRead', 'makeReadAll');
        Route::delete('deleteAll', 'deleteAll');
        Route::delete('delete/{id}', 'deleteById');
    });


Route::controller(StudentAuthController::class)
    ->prefix('auth/student')
    ->name('student.')
    ->group(function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
        Route::post('/logout', 'logout');
        Route::post('/refresh', 'refresh');
        Route::get('/student-profile', 'userProfile');
    });


Route::controller(TeacherAuthController::class)
    ->prefix('auth/teacher')
    ->name('teacher.')
    ->group(function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
        Route::post('/logout', 'logout');
        Route::post('/refresh', 'refresh');
        Route::get('/teacher-profile', 'userProfile');
        Route::get('verify/{token}', 'verify');
    });


Route::controller(PostController::class)
    ->prefix('post')->group(function () {
        Route::post('storepost', 'store')->middleware('auth:teacher');
        Route::get('show', 'index')->middleware('auth:admin');
        Route::delete('delete/{id}', 'deleteById');
        Route::get('show/{id}', 'allDataPost');
        Route::get('approved', 'showPostsApproved');
    });
