<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\v1\Controller;
use App\Http\Resources\v1\userResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Mail\OtpEmail;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = User::where('email', strtolower($credentials['email']))->first(); // get the user based one email


        if(!$user || !Hash::check($credentials["password"],$user->password)){
            return response()->json([
            'message' => 'invalid credentials',
            'status' => false
        ], 200);
        }

        //  if(!$user->hasVerifiedEmail()){
        // $otp = rand(1000, 9999); // 4-digit OTP

        // $user->otp = $otp;

        // $user->save();
        // Mail::to($user->email)->send( new OtpEmail(otp: $otp));
        //     return response()->json([
        //         'message' => 'unverified',
        //         'status' => false
        //     ], 200);

        // }
                // $user->save();
        // Mail::to($user->email)->send( new OtpEmail(otp: $otp));
        //     return response()->json([
        //         'message' => 'unverified',
        //         'status' => false
        //     ], 200);

        // }
        $token  = $user->createToken('mobile')->plainTextToken; // get the token


        return response()->json(['message' => 'success', 'status' => true, 'data' => ['token'=>$token,'user'=>new userResource($user->load('profile'))]]); // pass to frontend
    }
    public function logout(Request $request)
    {

       $request->user()->tokens()->delete();
        return response()->json([
                'message' => "Logged out successfully",
                'status' => true,

              ], 200);
    }



    public function forgetPassword(Request $request){
         $credentials = $request->validate([
            'email' => 'required|email',
        ]);
        $user = User::where('email',$credentials["email"])->first();

        if(!$user){
            return response()->json(['message' => 'invalid email','status' => false], 200);// get the user based one email
        }

        $otp = rand(1000, 9999); // 4-digit OTP

        $user->otp = $otp;

        $user->save();


        Mail::to($user->email)->send( new OtpEmail(otp: $otp));
        return response()->json(['message' => 'OTP sent successfully','status' => true], 200);// get the user based one email
    }


    public function submitOtp(Request $request){
        $credentials = $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|numeric|digits:4',
        ]);
        $user = User::where('email',operator: $credentials["email"])->first();
        if($user->otp == $credentials["otp"]){
            $user->markEmailAsVerified();
            $user->otp = null;
            $user->save();
            return response()->json(['status'=> true,'message' => 'OTP submitted successfully'],200);// get the user based one email
        }
        else{
            return response()->json(['status'=> false,'message' => 'invalid otp']);// get the user based one email
        }
    } //sendOtp


    public function changePassword(Request $request){
        $credentials = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ]);
        $user = User::where('email',$credentials["email"])->first();
        $user->password = Hash::make($credentials["password"]);
        $user->save();
        return response()->json(['status'=> true,"message" => "password changed successfully"],200);// get the user based one email
    }
}
