<?php
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get("/testing", function(Request $request) {
            broadcast(new \App\Events\PublicMessageSent("This is a test message"));
            return "Event has been sent!";
});
