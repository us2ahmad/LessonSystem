<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Post\PostReviewController;
use App\Http\Controllers\Student\{
    StudentAuthController,
    StudentOrderController
};
use Illuminate\Support\Facades\Route;


Route::prefix('student')->name('student.')->group(function () {
    Route::controller(StudentAuthController::class)->group(function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
        Route::post('/logout', 'logout');
        Route::get('/student-profile', 'userProfile');
        Route::get('verify/{token}', 'verify');
        Route::get('forgotPassword',  'forgotPassword');
        Route::post('resetPassword/{token}', 'resetPassword');
    });

    Route::controller(StudentOrderController::class)->prefix('/order')->group(function () {
        Route::post('add', 'addOrder');
    });
    Route::post('post/review', [PostReviewController::class, 'review'])->middleware('auth:student');
});
Route::get('pay/{servicesId}', [PaymentController::class, 'pay'])->middleware('auth:student');
