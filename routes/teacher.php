<?php

use App\Http\Controllers\Teacher\{
    FileController,
    TeacherAuthController,
    TeacherProfileController,
    TeacherOrderController
};
use Illuminate\Support\Facades\Route;

Route::prefix('teacher')->name('teacher.')->group(function () {
    Route::controller(TeacherAuthController::class)
        ->group(function () {
            Route::post('/login', 'login');
            Route::post('/register', 'register');
            Route::post('/logout', 'logout');
            Route::get('verify/{token}', 'verify');
            Route::get('forgotPassword',  'forgotPassword');
            Route::post('resetPassword/{token}', 'resetPassword');
        });
    Route::controller(TeacherOrderController::class)->prefix('order')
        ->group(function () {

            Route::get('show', 'showOrder');
            Route::post('updateStatus', 'updateStatus');
        });
    Route::controller(TeacherProfileController::class)->prefix('profile')
        ->group(function () {

            Route::get('/', 'profile');
            Route::get('edit', 'edit');
            Route::post('update', 'update');
            Route::post('delete', 'delete');
        });
    Route::controller(FileController::class)->prefix('file')
        ->group(function () {

            Route::get('export', 'export');
            Route::post('import', 'import');
        });
});
