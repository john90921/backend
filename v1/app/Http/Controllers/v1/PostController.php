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
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Http;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // return $request->user()->posts()->paginate(10);
        return post::paginate(10);
    }

    public function retrievePostById(Request $request){
        $data = $request->validate([
            'post_id' => 'required|integer',
        ]);

        $post = post::query()
            ->where("hide", false)
            ->with('user.profile')
            ->withCount(["likes as is_liked" => function ($query) use ($request){ $query->where("user_id", $request->user()->id);}])
            ->withCount("likes as total_likes")
            ->withCount("comments as comments_count")
            ->find($data['post_id']);

        if (!$post) {
            return response()->json([
                'status' => false,
                'message' => 'Post not found or hidden',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Post retrieved successfully',
            'data' => new postListResource($post)
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function communityPosts(Request $request){

        $todayPosts = post::query();

        if($request->has('date')){
        if( $request->date == 'today'){
            $todayPosts->whereDate('created_at', Carbon::today());
        }
        if( $request->date == 'week'){
            $todayPosts->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        }
        if( $request->date =='month'){
            $todayPosts->whereMonth('created_at', Carbon::now()->month);
        }
        if( $request->date == 'year'){
            $todayPosts->whereYear('created_at', Carbon::now()->year);
        }
    }

      $todayPosts->with('user.profile')
              ->where("hide", false)
        ->withCount(["likes as is_liked" => function ($query) use ($request){ $query->where("user_id", $request->user()->id);}])
        ->withCount("likes as total_likes")
        ->withCount("comments as comments_count");// Eager load the user relationship // get the post owner user info

        if($request->has('sortBy')){
        if($request->sortBy == 'latest'){

        $todayPosts->orderBy('created_at', 'desc');

        }
        if($request->sortBy == 'popular'){
        $todayPosts->orderBy('total_likes', 'desc');
        }

    }

 if($request->has('searchInput')){
        $search = $request->searchInput;

         $todayPosts->where('title', 'same', "%{$search}%")
              ->orWhere('content', 'like', "%{$search}%");
        }

    if($request->has('userId')){
        $todayPosts->where('user_id', $request->userId);
    }
       $todayPosts = $todayPosts->paginate(6);
        // $todayPosts= post::where('id',217)->get();
    return
        response()->json(
            [
                'message' => 'success',
                'status' => true,
                'data' => postListResource::collection(   $todayPosts),
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

    public function todayPosts(Request $request){

        $todayPosts = post::query()
        ->whereDate('created_at', Carbon::today())
        ->with('user.profile')
        ->where("hide", false)
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

        $data = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'image' =>'sometimes|nullable|String',
        ]);

//         if ($request->hasFile('image') && $request->file('image')!= null ) {
//         // $uploadFolder = 'posts';
//             // return $request->file('image')->getRealPath();

//         // $image_uploaded_path = $image->store( $uploadFolder, 'public');
// // dd(Cloudinary::upload('public/test.jpg')->getSecurePath());
//  $image = $request->file('image');
//         $imageBase64 = base64_encode(file_get_contents($image));
//  $response = Http::asForm()->post('https://api.imgbb.com/1/upload', [
//             'key' => env('IMGBB_API_KEY'),
//             'image' => $imageBase64,
//         ]);
//     // $image_uploaded_path = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
//  $dataImage = $response->json();

//        if (!isset($dataImage['data']['url'])) {

//         return response()->json(['status' => false,'message' => 'Image upload failed','data' => null], 500);

//        }

//         $image_uploaded_path = $dataImage['data']['url'];
//        }

        try {
           $post = post::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'image' => $data['image'] ?? null,
            'user_id' => $request->user()->id
           ]);
            return response()->json(['status' => true,'message' => 'post created successfully','data' =>new postListResource($post)], 201);

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
            'image' =>'sometimes|nullable',
            'remove_image' => 'sometimes|required',
        ]);

        if($request->remove_image == 'true'){
            // if($post->image != null){
            //     Storage::disk('public')->delete($post->image);
            // }
            $post->image = null;
            $post->save();
        }

//         if ($request->hasFile('image')) {
//         $image = $request->file('image');
//         $imageBase64 = base64_encode(file_get_contents($image));
//  $response = Http::asForm()->post('https://api.imgbb.com/1/upload', [
//             'key' => env('IMGBB_API_KEY'),
//             'image' => $imageBase64,
//         ]);
//     // $image_uploaded_path = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
//  $dataImage = $response->json();
//  $image_uploaded_path = $dataImage['data']['url'];



//         $data['image'] = $image_uploaded_path;
//         }


        $post->update($data);


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
    //         if ($post->image) {
    //     if (Storage::disk('public')->exists($post->image)) {
    //         Storage::disk('public')->delete($post->image);
    //     }
    // }
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
