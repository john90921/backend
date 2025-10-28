<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\AuthController;
use App\Http\Controllers\v1\LikeController;
use App\Http\Controllers\v1\PostController;
use App\Http\Controllers\v1\CommentController;
use App\Http\Controllers\v1\ReplyController;
use Illuminate\Support\Facades\Broadcast;
use App\Events\PrivateMessageSent;
use App\Http\Controllers\v1\NotificationController;
use App\Http\Controllers\v1\UserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::post('login', [AuthController::class, 'login'])->name('login');;
// Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::group(
    ['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\v1'],
function () {
        Route::resource('profile', 'ProfileController')->middleware('auth:sanctum');
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $user = \App\Models\User::find($request->route('id'));

    if (! $user) {
        return response()->json(['message' => 'Invalid user'], 404);
    }

    if (! hash_equals((string) $hash = sha1($user->getEmailForVerification()), (string) $request->route('hash'))) {
        return response()->json(['message' => 'Invalid verification link'], 400);
    }

    if ($user->hasVerifiedEmail()) {
        return response()->json(['message' => 'Already verified']);
    }

    $user->markEmailAsVerified();

    return response()->json(['message' => 'Email verified successfully']);
})->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
        Route::resource('user', 'UserController')->middleware('auth:sanctum');
        Route::resource('post', 'PostController')->middleware(middleware: 'auth:sanctum');
        Route::post('retrievePostById', action: [PostController::class, 'retrievePostById'])->middleware('auth:sanctum');

        Route::resource('comment', 'CommentController')->middleware('auth:sanctum');
        Route::resource('reply', 'ReplyController')->middleware('auth:sanctum');
        Route::resource('plant', 'PlantController');
        Route::resource('notification', 'NotificationController')->middleware('auth:sanctum');
        Route::post('unreadCount', action: [NotificationController::class, 'getUnreadCount'])->middleware('auth:sanctum');
        Route::post('markAsRead', action: [NotificationController::class, 'markAsRead'])->middleware('auth:sanctum');
        Route::get('getLoginUserInfo', [UserController::class, 'getLoginUserInfo'])->middleware('auth:sanctum');

        Route::post('register', [UserController::class, 'register']);

        Route::post('login', [AuthController::class, 'login'])->name('login');;
        Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
        Route::post('forgetPassword', [AuthController::class, 'forgetPassword']);
        Route::post('submitOtp', [AuthController::class, 'submitOtp']);
        Route::post('changePassword', [AuthController::class, 'changePassword']);
        Route::get("todayPosts", [PostController::class, 'todayPosts'])->middleware('auth:sanctum');
        Route::get("communityPosts", [PostController::class, 'communityPosts'])->middleware('auth:sanctum');

        Route::post("likes", [LikeController::class, 'store'])->middleware('auth:sanctum');
        Route::post("postComments", [CommentController::class, 'postComments'])->middleware('auth:sanctum');
        Route::post("commentReplies", [ReplyController::class, 'commentReplies'])->middleware('auth:sanctum');
        Route::post("broadcasting/auth", function(Request $request) {
            return Broadcast::auth($request);
        })->middleware('auth:sanctum');
        // Route::get("/broudcast-test", function(Request $request) {
        //     event(new \App\Events\PrivateMessageSent("This is a test message", $request->user()->id));
        //     return "Event has been sent!";
        // })->middleware('auth:sanctum');

        Route::middleware('auth:sanctum')->post('/broadcasting/auth', function (Request $request) {
    return Broadcast::auth($request);
});
Route::post('/send-private', function (Request $request) {
    event(new PrivateMessageSent(
        $request->message,
        $request->receiver_id
    ));

    return ['status' => 'Private message sent'];
});

    }
);


// Route::post(uri: 'login', [App\Http\Controllers\v1\AuthContoller::class, 'login']);
// Route::post(uri: 'logout', [App\Http\Controllers\v1\AuthContoller::class, 'logout'])->middleware('auth:sanctum');




