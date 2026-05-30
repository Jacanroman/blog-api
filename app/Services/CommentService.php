<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Recipe;
use Illuminate\Pagination\LengthAwarePaginator;

class CommentService
{

    //Admin list all comments with optional status filter

    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $perPage = $filters['per_page'] ?? 10;

        return Comment::with('recipe','user:id,name','replies')

        //Filters
        ->when(isset($filters['status']), fn($q)=>
            $q->where('status', $filters['status'])
        )
        ->whereNull('parent_id')
        ->latest()
        ->paginate($perPage);
    }

    public function getById(int $id): Comment
    {
        return Comment::with('recipe','user:id,name','parent','replies')
        ->where('id',$id)
        ->firstOrFail();
    }

    public function getForRecipe(string $slug): LengthAwarePaginator
    {
        $recipe = Recipe::where('slug',$slug)->firstOrFail();

        return Comment::with('user:id,name','replies.user:id,name')
        ->where('recipe_id',$recipe->id)
        ->where('status', 'approved')
        ->whereNull('parent_id')
        ->latest()
        ->paginate(20);
    }


    
    public function create(array $data, ?int $userId): Comment
    {
        return Comment::create([
            'recipe_id' => $data['recipe_id'],
            'user_id' => $userId,
            'parent_id' => $data['parent_id'] ?? null,
            'guest_name' => $userId ? null : ($data['guest_name'] ?? null),
            'guest_email' => $userId ? null : ($data['guest_email'] ?? null),
            'body' => $data['body'],
            'status' => 'pending'

        ]);
    }

    public function updateStatus(Comment $comment, string $status): Comment
    {
        $comment->update(['status' => $status]);

        return $comment -> fresh();
    }

    public function delete(Comment $comment): void
    {
        $comment->replies()->delete();

        $comment->delete();
    }
}