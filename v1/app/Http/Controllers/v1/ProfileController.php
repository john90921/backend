<?php

namespace App\Http\Controllers\v1;
use App\Http\Resources\v1\profileResource;
use App\Models\profile;

use App\Http\Requests\StoreprofileRequest;
use App\Http\Controllers\v1\Controller;
use App\Http\Resources\v1\userResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $personal_profile = $request->user()->profile; // get the profile of the authenticated user
        return new profileResource($personal_profile);
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
    public function store(StoreprofileRequest $request)
  {
        $image_uploaded_path = null;

        return profile::create([
            'name' => $request->name,
            'profile_image' => $image_uploaded_path,
            'phone' => $request->phone,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(profile $profile)
    {
        Gate::authorize('modify', $profile);
        return new profileResource($profile);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(profile $profile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, profile $profile)
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string',
            'description' =>'sometimes|nullable|string',
            'profile_image' => 'sometimes|nullable',
            'remove_image' => 'sometimes|required',
        ]);
           if($request->remove_image == 'true'){
            // if($post->image != null){
            //     Storage::disk('public')->delete($post->image);
            // }
            $profile->profile_image = null;
        }
       

    $profile->update($data);
     return response()->json([
        'status' => true,
        'message' => 'Profile updated successfully',
        'data' => new userResource( $request->user())
     ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(profile $profile)
    {
        Gate::authorize('modify', $profile);
        $profile->delete();
        return response()->json([
            'message' => 'Profile deleted successfully',
        ], 200);
    }
}
