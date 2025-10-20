<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\v1\Controller;

use App\Models\like;
use App\Http\Requests\UpdatelikeRequest;

use Illuminate\Http\Request;

class LikeController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
          "type" => "required|in:post,comment,reply",
          "id" => "required", // post_id, comment_id, reply_id
        ]);
        return toggle_like($request->type,$request->id,$request->user()->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(like $like)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatelikeRequest $request, like $like)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(like $like)
    {
        //
    }
}
