<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryService
{
    public function getAll()
    {
        return Category::withCount('recipes')->get();
    }

    public function getBySlug(string $slug): Category
    {
        return Category::where('slug',$slug)
        ->firstOrFail();
    }

    public function create(array $data): Category
    {
        return DB::transaction(function() use($data){
            return Category::create([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'description' => $data['description']
            ]);
        });
    }

    public function update(Category $category, array $data): Category
    {
        return DB::transaction(function() use($category,$data){
            $category->update([
                'name' => $data['name'] ?? $category->name,
                'slug' => isset($data['name']) ? Str::slug($data['name']) : $category->slug,
                'description' => $data['description'] ?? $category->description
            ]);

            return $category->fresh();
        });
    }

    public function delete(Category $category): void
    {
        $category->delete();
    }
}