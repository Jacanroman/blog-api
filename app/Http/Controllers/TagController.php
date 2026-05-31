<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tag\CreateTagRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use App\Services\TagService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function __construct(private readonly TagService $tagService){}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $tags = $this->tagService->getAll([
            'name' => $request->name
        ]);

        return TagResource::collection($tags)->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateTagRequest $request): JsonResponse
    {
        $tag = $this->tagService->store($request->validated());

        return response()->json(new TagResource($tag),201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        $this->tagService->delete($tag);

        return response()->json(['message' => 'Tag deleted'],200);
    }
}
