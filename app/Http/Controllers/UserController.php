<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private readonly UserService $userService){}
    // Admin — list all users
    public function index(Request $request): JsonResponse
    {
        $users = $this->userService->getAll(
            $request->only(['role','search','per_page'])
        );
          
        return UserResource::collection($users)->response();
    }

    public function show(User $user): JsonResponse
    {
        $user = $this->userService->getById($user);
        return response()->json(new UserResource($user));
    }

    public function store(CreateUserRequest $request): JsonResponse
    {
        $user = $this->userService->create($request->validated());

        return response()->json(new UserResource($user),201);
    }

    // Admin — update role
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $user = $this->userService->update($user, $request->validated());

        return response()->json(new UserResource($user));
    }
}