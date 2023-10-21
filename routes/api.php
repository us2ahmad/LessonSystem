<?php

use App\Http\Controllers\Post\{
    PostController,
    PostReviewController
};
use Illuminate\Support\Facades\Route;

require 'admin.php';    // Admin Route
require 'student.php';  // Student Route
require 'teacher.php';  // Teacher Route


Route::get('unauthorized', function () {
    return response()->json(
        ['message' => 'Unauthorized'],
        401
    );
})->name('login');



Route::prefix('post')->group(function () {
    Route::controller(PostController::class)->group(function () {

        Route::post('storepost', 'store')->middleware('auth:teacher');
        Route::delete('delete/{id}', 'deleteById')->middleware('auth:teacher');
        Route::get('show', 'index')->middleware('auth:admin');
        Route::get('show/{id}', 'allDataPost');
        Route::get('approved', 'showPostsApproved');
    });



    Route::controller(PostReviewController::class)
        ->group(function () {
            Route::get('rate/{id}', 'postRate');
        });
});
