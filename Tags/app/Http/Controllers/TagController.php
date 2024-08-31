<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\storeTagContoller;
use App\Http\Requests\storeTagRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Exception;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::all();
        if($tags){
            return ApiResponse::sendResponse(200,'All Tags', TagResource::collection($tags));
        }
            return ApiResponse::sendResponse(404,'No Tags Avilable');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(storeTagRequest $request)
    {
        $tag = Tag::create($request->validated());
        if($tag){
            return ApiResponse::sendResponse(201,"Tag Created Successfully",new TagResource($tag));
        }
            return ApiResponse::sendResponse(500, 'Failed to Create Tag');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $tag = Tag::findOrFail($id);
        if($tag){
            return ApiResponse::sendResponse(200,"Tag Retrived Successfully",new TagResource($tag));
        }
            return ApiResponse::sendResponse(200,"Fail to Retrive Tag");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(storeTagRequest $request, $id)
    {
        try {
            $tag = Tag::findOrFail($id);
            $tag->update($request->validated());

            return ApiResponse::sendResponse(200, 'Tag updated successfully',new TagResource($tag));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Fail to update Tag', ['error' => $e->getMessage()]);
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        try {
            $tag = Tag::findOrFail($id);
            $tag->delete();
            return ApiResponse::sendResponse(200,"Tag Successfully Deleted");
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500, 'Failed to delete Tag', ['error' => $e->getMessage()]);
        }
    }
}
