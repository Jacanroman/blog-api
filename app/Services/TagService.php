<?php

namespace App\Services;

use App\Models\Tag;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class TagService
{

    public function getAll(array $filters=[]): LengthAwarePaginator
    {
        $perPage = $filters['per_page'] ?? 10;

        return Tag::withCount('recipes')
        ->when(isset($filters['name']), fn($q) =>
            $q->where('name', 'like', "%{$filters['name']}%")
        )
        ->latest()
        ->paginate($perPage);
    }

    public function store(array $data): Tag
    {
        return Tag::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name'])
        ]);
    }

    public function delete(Tag $tag): Void
    {
        $tag->recipes()->detach();

        $tag->delete();
    }
}