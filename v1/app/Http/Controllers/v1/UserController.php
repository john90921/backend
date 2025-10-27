<?php

namespace App\Http\Controllers\v1;
use App\Http\Controllers\v1\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\v1\userResource;
use App\Mail\OtpEmail;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //     return User::paginate(10);
    // }
    public function getLoginUserInfo(Request $request)
    {
        $user = $request->user();

        if($user != null){
           return  response()->json([
            'status' => true,
            'message' => 'user retrieved successfully',
            'data' => new userResource($user)
           ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'No authenticated user',
            'data' => null
        ], 401);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    // try{
    //     $data = $request->validate([
    //         'name' => 'required|string',
    //         'email' => 'required|string|email|max:255|unique:users',
    //         'password' => 'required|string',

    //     ]);

    //     // $randomPassword = Str::random(12);
    //     $password = $data['password'];
    //     $userData =[
    //         'name' =>  $data['name'],
    //         'email' => $data['email'],
    //         'password' => Hash::make($password),
    //         'department' => $data['department'] ?? 'general',
    //     ];

    //     $user = User::create( $userData );

    //     $user->profile()->create(
    //         [
    //              'name' => $request->name
    //         ]
    //     );
    //     if($request->role == 'worker'){
    //         $user->worker()->create(
    //             [
    //                 'department' => $request->department ?? 'general',
    //             ]
    //         );
    //     }
    //       $token  = $user->createToken('mobile')->plainTextToken;
    //     // Mail::to($user->email)->send(new WelcomeEmail($user, $password));
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'User created successfully',
    //         'data' => ['token'=>$token,'user'=>new userResource($user->load('profile'))],
    //     ]);
    // }catch(\Exception $e){
    //         return response()->json([
    //         'status' => false,
    //         'message' => 'Server error: ' . $e->getMessage(),
    //     ], 500);
    // }
    }
     public function register(Request $request)
    {
    try{
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string',

        ]);
        // $randomPassword = Str::random(12);
        if($data['email'])

        $password = $data['password'];
        // $userData = [
        //     'name' =>  $data['name'],
        //     'email' => $data['email'],
        //     'password' => Hash::make($password),
        //     'department' => $data['department'] ?? 'general',
        // ];

        $user = User::create( [
            'name' =>  $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($password),
            'department' => $data['department'] ?? 'general',
        ] );
        $user->profile()->create(
            [
                 'name' => $request->name
            ]
        );


        if($request->role == 'worker'){
            $user->worker()->create(
                [
                    'department' => $request->department ?? 'general',
                ]
            );
        }
        $otp = rand(1000, 9999); // 4-digit OTP

        $user->otp = $otp;

        $user->save();
        Mail::to($user->email)->send( new OtpEmail(otp: $otp));

        //   $token  = $user->createToken('mobile')->plainTextToken;
        // // Mail::to($user->email)->send(new WelcomeEmail($user, $password));
        // return response()->json([
        //     'status' => true,
        //     'message' => 'User created successfully',
        //     'data' => ['token'=>$token,'user'=>new userResource($user->load('profile'))],
        // ]);
        return response()->json([
            'status' => true,
            'message' => 'User created successfully, OTP sent to email for verification',

        ]);
    }catch(\Exception $e){
            return response()->json([
            'status' => false,
            'message' => $e->getMessage(),
        ], );
    }
    }

    /**
     * Display the specified resource.
     */
    // public function show(User $user)
    // {
    //     return $user;
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, User $user)
    // {
    //     $data = $request->validate([
    //         'name' => 'sometimes|required|string|email|max:255|unique:users',
    //         'password' => 'sometimes|required|string',
    //         'role' => 'sometimes|required|string|in:staff,manager',
    //     ]);

    //     $user->update($data);
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            'message' => 'User deleted successfully',
        ], 200);
    }
}
