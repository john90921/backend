<?php

namespace App\Http\Controllers\v1;
use App\Http\Controllers\v1\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return User::all();
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
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|string|in:worker,manager',
            'department' => 'sometimes|string',
        ]);
        $randomPassword = Str::random(12);
        $randomPassword = 'password';
        $userData =[
            'name' =>  $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($randomPassword),
            'role' => $data['role'],
            'department' => $data['department'] ?? 'general',
        ];

        $user = User::create( $userData );

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

        Mail::to($user->email)->send(new WelcomeEmail($user, $randomPassword));
        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return $user;
    }

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
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'email' => 'sometimes|required|string|email|max:255|unique:users',
            'password' => 'sometimes|required|string',
            'role' => 'sometimes|required|string|in:staff,manager',
        ]);

        $user->update($data);
    }

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
