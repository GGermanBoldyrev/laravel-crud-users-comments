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
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        $comment = $this->service->create($data);
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

    public function userToActivePosts(int $userId): ResourceCollection
    {
        $comments = $this->service->getUserCommentsToActivePosts($userId, request('per_page', 15));
        return CommentResource::collection($comments);
    }

    public function mine(): ResourceCollection
    {
        $comments = $this->service->getCreatedByCurrentUser(request('per_page', 15));
        return CommentResource::collection($comments);
    }

    public function byPost(int $postId): ResourceCollection
    {
        $comments = $this->service->getByPost($postId, request('per_page', 15));
        return CommentResource::collection($comments);
    }

    public function replies(int $commentId): ResourceCollection
    {
        $comments = $this->service->getReplies($commentId, request('per_page', 15));
        return CommentResource::collection($comments);
    }
}
