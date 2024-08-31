<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Traits\UploadImageTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    use UploadImageTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $posts = $request->user()->posts()->with('tags')->get();
        if($posts){
            return ApiResponse::sendResponse(200,'All Posts For this User',PostResource::collection($posts));
        }
            return ApiResponse::sendResponse(404,'No Posts');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        try {

            $coverImagePath = $this->uploadImage($request, 'cover_image', 'cover_images');

            $postData = array_merge($request->validated(), ['cover_image' => $coverImagePath]);

            $post = $request->user()->posts()->create($postData);

            $post->tags()->sync($request->input('tags'));

            $createdPost = $post->load('tags');

            return ApiResponse::sendResponse(201,'Post Created Successfully',new PostResource($createdPost));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(404,'Fail to Create Post');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        try {
            $post = $request->user()->posts()->with('tags')->findOrFail($id);
            return ApiResponse::sendResponse('200','All Posts With Tags',new PostResource($post));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(404,'Fail to Retrive Post');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, $id)
    {
        try {

            $post = $request->user()->posts()->findOrFail($id);
            $validatedData = $request->validated();

            if ($request->hasFile('cover_image')) {
                $this->replaceCoverImage($post, $request);
            }

            $post->update($validatedData);

            if ($request->has('tags')) {
                $post->tags()->sync($validatedData['tags']);
            }

            $updatedPost = $post->load('tags');
            return ApiResponse::sendResponse(200,'Post Updated Successfully',new PostResource($updatedPost));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(404,'Fail to Update Post');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $post = $request->user()->posts()->findOrFail($id);
            $post->delete();

            return ApiResponse::sendResponse(200,'Post Deleted Successfully');
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500,'Fail to Delete Post');
        }
    }

    public function viewDeleted(Request $request)
    {
        try {
            $posts = $request->user()->posts()->onlyTrashed()->with('tags')->get();
            return ApiResponse::sendResponse(200,'Deleted Posts', PostResource::collection($posts));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500,'Fail to Retrive Deleted Posts');
        }
    }

    public function restore(Request $request, $id)
    {
        try {

            $post = $request->user()->posts()->onlyTrashed()->findOrFail($id);
            $post->restore();
            $restordPost = $post->load('tags');

            return ApiResponse::sendResponse(200,'Deleted Post Restored Successfully', $restordPost);
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500,'Fail to Restore Deleted Post');
        }

    }

    private function replaceCoverImage($post, $request)
    {
        if ($post->cover_image) {
            // dd($post->cover_image);
            Storage::disk('public')->delete($post->cover_image);
        }

        $path = $this->uploadImage($request, 'cover_image', 'cover_images');
        $post->update(['cover_image' => $path]);
        // dd($post);
    }
}
