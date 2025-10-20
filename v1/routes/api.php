<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\AuthController;
use App\Http\Controllers\v1\LikeController;
use App\Http\Controllers\v1\PostController;
use App\Http\Controllers\v1\CommentController;
use App\Http\Controllers\v1\ReplyController;
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('login', [AuthController::class, 'login'])->name('login');;
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::group(
    ['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\v1'],
function () {
        Route::resource('profile', 'ProfileController')->middleware('auth:sanctum');
        Route::resource('user', 'UserController');
        Route::resource('post', 'PostController')->middleware('auth:sanctum');
        Route::resource('comment', 'CommentController')->middleware('auth:sanctum');
        Route::resource('reply', 'ReplyController')->middleware('auth:sanctum');

        Route::resource('notification', 'NotificationController')->middleware('auth:sanctum');
        Route::post('login', [AuthController::class, 'login'])->name('login');;
        Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
        Route::post('forgetPassword', [AuthController::class, 'forgetPassword']);
        Route::post('submitOtp', [AuthController::class, 'submitOtp']);
        Route::post('changePassword', [AuthController::class, 'changePassword']);
        Route::get("todayPosts", [PostController::class, 'todayPosts'])->middleware('auth:sanctum');
        Route::post("likes", [LikeController::class, 'store'])->middleware('auth:sanctum');
        Route::post("postComments", [CommentController::class, 'postComments'])->middleware('auth:sanctum');
        Route::post("commentReplies", [ReplyController::class, 'commentReplies'])->middleware('auth:sanctum');

}
);

// Route::post(uri: 'login', [App\Http\Controllers\v1\AuthContoller::class, 'login']);
// Route::post(uri: 'logout', [App\Http\Controllers\v1\AuthContoller::class, 'logout'])->middleware('auth:sanctum');




