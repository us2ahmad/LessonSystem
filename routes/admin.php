<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\PostStatusController;
use App\Http\Controllers\Admin\TeacherStatusController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->group(function () {
        Route::controller(AdminAuthController::class)->group(function () {
            Route::post('login', 'login');
            Route::post('logout', 'logout');
            Route::get('admin-profile', 'userProfile');
            Route::get('forgotPassword', 'forgotPassword');
            Route::post('resetPassword/{token}', 'resetPassword');
        });
        Route::controller(PostStatusController::class)->group(function () {
            Route::post('post/change-status', 'changeStatus');
        });
        Route::controller(TeacherStatusController::class)->group(function () {
            Route::post('teacher/change-status', 'changeStatus');
        });
        Route::controller(AdminNotificationController::class)
            ->prefix('notification')->group(function () {
                Route::get('getAll', 'index');
                Route::get('unread', 'unread');
                Route::post('makeRead', 'makeReadAll');
                Route::delete('deleteAll', 'deleteAll');
                Route::delete('delete/{id}', 'deleteById');
            });
    });
