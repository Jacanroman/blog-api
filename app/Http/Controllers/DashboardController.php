<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'stats' => [
                'recipes' => [
                    'total'     => Recipe::count(),
                    'published' => Recipe::where('status', 'published')->count(),
                    'draft'     => Recipe::where('status', 'draft')->count(),
                    'archived'  => Recipe::where('status', 'archived')->count(),
                ],
                'comments' => [
                    'total'    => Comment::count(),
                    'pending'  => Comment::where('status', 'pending')->count(),
                    'approved' => Comment::where('status', 'approved')->count(),
                    'rejected' => Comment::where('status', 'rejected')->count(),
                ],
                'categories' => Category::count(),
                'users'      => User::count(),
                'countries'  => Recipe::whereNotNull('country')
                                      ->distinct('country')
                                      ->count(),
            ],
        ]);
    }
}