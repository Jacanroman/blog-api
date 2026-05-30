<?php

namespace App\Http\Controllers;

use App\Http\Requests\Recipe\CreateRecipeRequest;
use App\Http\Requests\Recipe\UpdateRecipeRequest;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use App\Services\RecipeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function __construct(
        private readonly RecipeService $recipeService
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $recipes = $this->recipeService->getAll([
            'search'     => $request->search,
            'category'   => $request->category,
            'country'    => $request->country,
            'difficulty' => $request->difficulty,
            'tag'        => $request->tag,
        ]);

        return RecipeResource::collection($recipes)->response();
    }

    public function adminIndex(Request $request): JsonResponse
    {
        $recipes = $this->recipeService->getAll([
            'admin'    => true,
            'status'   => $request->status,
            'search'   => $request->search,
            'per_page' => 15,
        ]);

        return RecipeResource::collection($recipes)->response();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $recipe = $this->recipeService->getBySlug($slug);

        return (new RecipeResource($recipe))->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRecipeRequest $request)
    {
        $recipe = $this->recipeService->create(
            $request->validated(),
            $request->user()->id
            );

        return new RecipeResource($recipe);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRecipeRequest $request, Recipe $recipe)
    {
        $recipe = $this->recipeService->update($recipe, $request->validated());

        return (new RecipeResource($recipe))->response();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recipe $recipe)
    {
        //
    }
}
