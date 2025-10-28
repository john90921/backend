<?php

namespace App\Http\Controllers\v1;
use App\Http\Controllers\v1\Controller;
use App\Models\plant;
use App\Http\Requests\StoreplantRequest;
use App\Http\Requests\UpdateplantRequest;
use App\Http\Resources\v1\plantResource;
use Illuminate\Http\Request;


class PlantController extends Controller
{
/**
     * Display a listing of the resource.
     */
    public function index()
    {
        return plantResource::collection(plant::all());
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
        $image_uploaded_path = null;
        try{
            $data = $request->validate([
            'name' => 'required|string',
            'image' =>'required',
        ]);
         if ($request->hasFile('image') || $request->file('image')!= null ) {
        $uploadFolder = 'plants';
        $image = $request->file('image');
        $image_uploaded_path = $image->store( $uploadFolder, 'public');
       }

        $plant = plant::create([
            'name' => $data['name'],
            'image' => $image_uploaded_path,
            'user_id' => $request->user()->id
           ]);
            return response()->json(['status' => true,'message' => 'Plant created successfully','data' =>new plantResource($plant)], 201);
    } catch (\Exception $e) {
            return response()->json(['status' => false,'message' => 'Plant not created successfully', 'error' => $e->getMessage()], 500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(plant $plant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(plant $plant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateplantRequest $request, plant $plant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(plant $plant)
    {
        //
    }
}
