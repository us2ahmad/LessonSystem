<?php

use App\Http\Controllers\Admin\{AdminAuthController, AdminNotificationController, PostStatusController};
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Student\{StudentAuthController, StudentOrderController};
use App\Http\Controllers\Teacher\{TeacherAuthController, TeacherOrderController, TeacherProfileController};
use App\Http\Controllers\Post\{PostController, PostReviewController};
use Illuminate\Support\Facades\Route;

Route::get('unauthorized', function () {
    return response()->json(
        ['message' => 'Unauthorized'],
        401
    );
})->name('login');

######################Admin Route######################
Route::prefix('admin')
    ->group(function () {
        Route::controller(AdminAuthController::class)->group(function () {
            Route::post('login', 'login');
            Route::post('logout', 'logout');
            Route::get('admin-profile', 'userProfile');
        });
        Route::controller(PostStatusController::class)->group(function () {
            Route::post('post/change-status', 'changeStatus');
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
#############################################################################

Route::prefix('student')->name('student.')->group(function () {
    Route::controller(StudentAuthController::class)->group(function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
        Route::post('/logout', 'logout');
        Route::get('/student-profile', 'userProfile');
        Route::get('verify/{token}', 'verify');
        Route::get('forgetpassword',  'forgetpassword');
        Route::post('resetpassword/{token}', 'checkToken');
        // Route::post('updatepassword',  'updatepassword');
    });
    Route::controller(StudentOrderController::class)->prefix('/order')->group(function () {
        Route::post('add', 'addOrder');
    });
    Route::post('review', [PostReviewController::class, 'review'])->middleware('auth:student');
});


Route::prefix('teacher')->name('teacher.')->group(function () {
    Route::controller(TeacherAuthController::class)
        ->group(function () {
            Route::post('/login', 'login');
            Route::post('/register', 'register');
            Route::post('/logout', 'logout');
            Route::get('verify/{token}', 'verify');
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
});


Route::prefix('post')->group(function () {

    Route::controller(PostController::class)->group(function () {
        Route::post('storepost', 'store')->middleware('auth:teacher');
        Route::get('show', 'index')->middleware('auth:admin');
        Route::delete('delete/{id}', 'deleteById');
        Route::get('show/{id}', 'allDataPost');
        Route::get('approved', 'showPostsApproved');
    });
    Route::controller(PostReviewController::class)
        ->group(function () {
            Route::get('rate/{id}', 'postRate');
        });
});

Route::get('pay', [PaymentController::class, 'pay'])->middleware('auth:student');
