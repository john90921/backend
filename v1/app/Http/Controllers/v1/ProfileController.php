<?php

namespace App\Http\Controllers\v1;
use App\Http\Resources\v1\profileResource;
use App\Models\profile;
use App\Http\Requests\StoreprofileRequest;
use App\Http\Requests\UpdateprofileRequest;
use App\Http\Controllers\v1\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;


class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $personal_profile = $request->user()->profile()->get(); // get the profile of the authenticated user
        return $personal_profile;
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
        if ($request->hasFile('profile_image')) {
        $uploadFolder = 'profiles';
        $image = $request->file('profile_image');
        $image_uploaded_path = $image->store( $uploadFolder, 'public');
       }

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
    public function update(UpdateprofileRequest $request, profile $profile)
    {
        $profile_data = $request->all();
        if($request->hasFile('profile_image')){//check if image present
            $uploadFolder = 'profiles';  //folder name
        $image = $request->file('profile_image'); // get image // if text, than use $request->all(), if file, use $request->file('key')
            $image_uploaded_path = $image->store($uploadFolder, 'public'); // store image in public disk (storage/app/public)
            $profile['profile_image'] = $image_uploaded_path;
        }

     $request->user()->profile()->update($profile_data);
     return response()->json([
        'message' => 'Profile updated successfully',
        'profile' => $profile
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
