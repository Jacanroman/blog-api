<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\CreateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CommentController extends Controller
{
    public function __construct(private readonly CommentService $commentService){}
    /**
     * Display a listing of the resource.
     */
    public function index(string $slug): AnonymousResourceCollection
    {
        // public get /api/recipes/{slug}/comments

        $comments = $this->commentService->getForRecipe($slug);

        return CommentResource::collection($comments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCommentRequest $request): JsonResponse
    {
        $comment = $this->commentService->create(
            $request->validated(),
            auth('sanctum')->id()
        );

        return response()->json(new CommentResource($comment), 201);
    }

    // ── Admin: GET /api/v1/admin/comments ─────────────────────────────────

    public function adminIndex(Request $request): AnonymousResourceCollection
    {
        $comments = $this->commentService->getAll($request->only(['status', 'per_page']));

        return CommentResource::collection($comments);
    }

    // ── Admin: PATCH /api/v1/admin/comments/{comment} ─────────────────────

    public function updateStatus(Request $request, Comment $comment): JsonResponse
    {
        $request->validate([
            'status' => ['required', 'in:pending,approved,rejected'],
        ]);

        $comment = $this->commentService->updateStatus($comment, $request->status);

        return response()->json(new CommentResource($comment));
    }

    // ── Admin: DELETE /api/v1/admin/comments/{comment} ────────────────────

    public function destroy(Comment $comment): JsonResponse
    {
        $this->commentService->delete($comment);

        return response()->json(['message' => 'Comment deleted'], 200);
    }
}
