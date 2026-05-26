<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Admin — list all users
    public function index(): JsonResponse
    {
        $users = User::with('roles')
            ->latest()
            ->paginate(20);

        return response()->json($users);
    }

    // Admin — update role
    public function update(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'role' => 'required|in:admin,author,reader',
        ]);

        $user->syncRoles([$validated['role']]);

        return response()->json($user->load('roles'));
    }
}