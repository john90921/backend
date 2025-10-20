<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\v1\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index(){
        return request()->user()->notifications;
    }
    public function markAsRead($request){
       $data = $request->validate([
            'notification_id' => 'required',
        ]);
        $notification = request()->user()->notifications()->where('id', $data['notification_id'])->first();
        if($notification){
            $notification->markAsRead();
            return response()->json(['status' => true,'message' => 'Notification marked as read'], 200);
    }
    return response()->json(['status' => false,'message' => 'Notification not found'], 404);
    //
}
}
