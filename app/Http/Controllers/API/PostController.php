<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
// use Response;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    // public function index(){
    //     $posts = Post::get();
    //     $msg = ["ok"];
    //     return response($posts,200,$msg);
    // }
    // public function index(){
    //     $posts = Post::get();
    // $array = [
    //     'data' => $posts,
    //     'message'=>'ok',
    //     'status' =>200
    // ];
    //     return response($array);
    // }
    /*============================*/
    use ApiResponsetrait;
    /*============================*/
    public function index()
    {
        $posts = PostResource::collection(Post::get());
        // $posts = Post::get();
        return $this->apiRespone($posts, 'ok', 200);
    }
    /*============================*/
    public function show($id)
    {
        $post = Post::find($id);
        // $post= new PostResource(Post::find($id));//no new object
        if ($post) {
            return $this->apiRespone(new PostResource($post), 'ok', 200);
        } else {
            return $this->apiRespone(null, 'this post not found', 404);
        }
    }
    /*============================*/
    public function store(request $request)
    {
        // validation
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'body' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->apiRespone(null, $validator->errors(), 400);
        }
        // store
        $post = Post::create($request->all());
        if ($post) {
            return $this->apiRespone(new PostResource($post), 'the post was successfully saved', 201);
        }
        return $this->apiRespone(null, 'the post was not saved', 400);
    }
    /*============================*/
    public function update(request $request, $id)
    {
        // validation
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'body' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->apiRespone(null, $validator->errors(), 400);
        }
        // $this->validatePost(new validator);
        $post = Post::find($id);
        if (!$post) {
            return $this->apiRespone(null, 'this post not found', 404);
        }
        $post->update($request->all());
        if ($post) {
            return $this->apiRespone(new PostResource($post), 'the post was successfully updated', 202); //202 accepted
        }
    }
    /*============================*/
    public function destroy($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return $this->apiRespone(null, 'this post not found', 404);
        }
        $post->delete();
        if ($post) {
            return $this->apiRespone(null, 'the post was deleted', 200);
        }
    }
    /*============================*/
}
