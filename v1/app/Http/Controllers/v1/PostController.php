<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\v1\Controller;
use App\Models\post;
use Illuminate\Http\Request;
use App\Http\Requests\UpdatepostRequest;
use Carbon\Carbon;
use  App\Http\Resources\v1\postListResource;
use App\Http\Resources\v1\postResource;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return post::paginate(10);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function todayPosts(Request $request){

        $todayPosts = post::query()
        ->whereDate('created_at', Carbon::today())
        ->with('user.profile')
        ->withCount(["likes as is_liked" => function ($query) use ($request){ $query->where("user_id", $request->user()->id);}])
        ->withCount("likes as total_likes")
        ->withCount("comments as comments_count")// Eager load the user relationship // get the post owner user info
        ->orderBy('created_at', 'desc')
        ->paginate(6);
        // $todayPosts= post::where('id',217)->get();
        return
        response()->json(
            [
                'message' => 'success',
                'status' => true,
                'data' => postListResource::collection($todayPosts),
                'pagination' => [
                    'total' => $todayPosts->total(),
                    'per_page' => $todayPosts->perPage(),
                    'current_page' => $todayPosts->currentPage(),
                    'last_page' => $todayPosts->lastPage(),
                    'from' => $todayPosts->firstItem(),
                    'to' => $todayPosts->lastItem(),
                ]
                ],200
            );

    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $image_uploaded_path = null;
        $data = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'image' =>'sometimes|required|image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:max_width=3000,max_height=3000',
        ]);

        if ($request->hasFile('image') || $request->file('image')!= null ) {
        $uploadFolder = 'posts';
        $image = $request->file('image');
        $image_uploaded_path = $image->store( $uploadFolder, 'public');
       }


        try {
           $post = post::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'image' => $image_uploaded_path,
            'user_id' => $request->user()->id
           ]);
            return response()->json(['status' => true,'message' => 'Comment created successfully','data' =>new postListResource($post)], 201);

        } catch (\Exception $e) {
            return response()->json(['status' => false,'message' => $e->getMessage(),'data' => null], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(post $post)
    {
        return $post;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(post $post, Request $request)
    {
        return $post;
    }
    //  catch(\Exception $e){
    //     return response()->json([
    //             'status' => false,
    //             'message' => $e->getMessage(),
    //         ], 500);
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, post $post)
    {


        try{
            $data = $request->validate([
            'title' => 'sometimes|required|string',
            'content' => 'sometimes|required|string',
            'image' =>'sometimes|nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:max_width=3000,max_height=3000',
            'remove_image' => 'sometimes|required',
        ]);


        if ($request->hasFile('image')) {
        $uploadFolder = 'posts';
        $image = $request->file('image');
        $image_uploaded_path = $image->store( $uploadFolder, 'public');
        $data['image'] = $image_uploaded_path;
        }


        $post->update($data);

        if ($request->has('remove_image') &&  $data["remove_image"] == true) {

             if (Storage::disk('public')->exists($post->image)) {
            Storage::disk('public')->delete($post->image);
        }
         $post->image = null;
            $post->save();
        }
        $newPost = post::with('user.profile')
            ->withCount(["likes as is_liked" => function ($query) use ($request){ $query->where("user_id", $request->user()->id);}])
            ->withCount("likes as total_likes")
            ->withCount("comments as comments_count")
            ->find($post->id);
        return response()->json([
                'status' => true,
                'message' => 'Post updated successfully',
                'data' => new postListResource($newPost),
            ], 200);}

            catch(\Exception $e){

        return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    //  catch(\Exception $e){
    //     return response()->json([
    //             'status' => false,
    //             'message' => $e->getMessage(),
    //         ], 500);
    // }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(post $post)
    {
        try{
            if ($post->image) {
        if (Storage::disk('public')->exists($post->image)) {
            Storage::disk('public')->delete($post->image);
        }
    }
            $post->delete();

            return response()->json([
                'status' => true,
                'message' => 'Post deleted successfully',
            ], 200);
        } catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
