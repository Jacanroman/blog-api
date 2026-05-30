<?php
namespace App\Services;

use App\Models\Recipe;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class RecipeService
{
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $perPage = $filters['per_page'] ?? 10;

        return Recipe::with(['category','user:id,name','tags','media'])
            ->withCount('comments')

        //If not admin - only show published recipes
            ->when(!($filters['admin'] ?? false), fn($q)=>
                $q->published()
            )

        //Filters
            ->when(isset($filters['status']), fn($q)=>
                $q->where('status', $filters['status'])
            )
            ->when(isset($filters['category']), fn($q) =>
                $q->byCategory((int) $filters['category'])
            )
            ->when(isset($filters['country']), fn($q) =>
                $q->byCountry($filters['country'])
            )
            ->when(isset($filters['difficulty']), fn($q) =>
                $q->byDifficulty($filters['difficulty'])
            )
            ->when(isset($filters['tag']), fn($q) =>
                $q->whereHas('tags', fn($t) =>
                    $t->where('slug', $filters['tag'])
                )
            )
            ->when(isset($filters['search']), fn($q) =>
                $q->where(function ($s) use ($filters) {
                    $s->where('title', 'like', "%{$filters['search']}%")
                      ->orWhere('excerpt', 'like', "%{$filters['search']}%")
                      ->orWhere('country', 'like', "%{$filters['search']}%")
                      ->orWhere('region', 'like', "%{$filters['search']}%");
                })
            )
            ->when(isset($filters['author']), fn($q) =>
                $q->where('user_id', $filters['author'])
            )
            ->latest($filters['admin'] ?? false ? 'created_at' : 'published_at')
            ->paginate($perPage);
    }

    public function getBySlug(string $slug, bool $adminMode = false): Recipe
    {
        return Recipe::with([
            'category', 'user:id,name', 'tags', 'media',
            'comments' => fn($q) => $q->where('status','approved')
                                        ->whereNull('parent_id')
                                        ->with('replies.user:id,name', 'user:id, name'),
        ])
        ->withCount('comments')
        ->where('slug',$slug)
        ->when(! $adminMode, fn($q)=> $q->published())
        ->firstOrFail();
    }

    public function create(array $data, int $userId): Recipe
    {
        $recipe = Recipe::create([
            'title'       => $data['title'],
            'slug'        => Str::slug($data['title']),
            'excerpt'     => $data['excerpt'] ?? null,
            'description' => $data['description'] ?? null,
            'ingredients' => $data['ingredients'] ?? null,
            'steps'       => $data['steps'] ?? null,
            'prep_time'   => $data['prep_time'] ?? null,
            'cook_time'   => $data['cook_time'] ?? null,
            'servings'    => $data['servings'] ?? 4,
            'difficulty'  => $data['difficulty'] ?? 'medium',
            'country'     => $data['country'] ?? null,
            'region'      => $data['region'] ?? null,
            'category_id' => $data['category_id'] ?? null,
            'user_id'     => $userId,
            'status'      => $data['status'] ?? 'draft',
            'published_at'=> ($data['status'] ?? '') === 'published' ? now() : null,
        ]);

        if (! empty($data['tags'])) {
            $recipe->tags()->sync($data['tags']);
        }

        return $recipe->load(['category', 'tags']);
    }

    public function update(Recipe $recipe, array $data): Recipe
    {
        //Regenerate slug only if tittle changed
        if (isset($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        //Set publishde_at only when first published
        if (
        isset($data['status']) &&
        $data['status'] === 'published' &&
        ! $recipe->published_at
        ) {
            $data['published_at'] = now();
        }

        $recipe->update($data);

        // Sync tags only if provided
        if (isset($data['tags'])) {
            $recipe->tags()->sync($data['tags']);
        }

        return $recipe->fresh(['category', 'tags']);

    }
}
