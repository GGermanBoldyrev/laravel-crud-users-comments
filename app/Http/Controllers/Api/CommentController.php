<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\CommentStoreRequest;
use App\Http\Requests\Comment\CommentUpdateRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Services\Contracts\CommentServiceInterface;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CommentController extends Controller
{
    public function __construct(private readonly CommentServiceInterface $service) {}

    public function index(): ResourceCollection
    {
        $filters = request()->only(['commentable_type','commentable_id']);
        $comments = $this->service->paginate($filters, request('per_page', 15));
        return CommentResource::collection($comments);
    }

    public function store(CommentStoreRequest $request): CommentResource
    {
        $comment = $this->service->create($request->validated());
        $comment->load(['user'])->loadCount('replies');
        return new CommentResource($comment);
    }

    public function show(Comment $comment): CommentResource
    {
        $comment->load(['user'])->loadCount('replies');
        return new CommentResource($comment);
    }

    public function update(CommentUpdateRequest $request, Comment $comment): CommentResource
    {
        $comment = $this->service->update($comment, $request->validated());
        $comment->load(['user'])->loadCount('replies');
        return new CommentResource($comment);
    }

    public function destroy(Comment $comment)
    {
        $this->service->delete($comment);
        return response()->noContent();
    }
}
