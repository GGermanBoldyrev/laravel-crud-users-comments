<?php

namespace App\Http\Controllers\Api;

use App\DTO\Comment\CommentFilterDto;
use App\DTO\Common\PageParams;
use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\CommentStoreRequest;
use App\Http\Requests\Comment\CommentUpdateRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Services\Contracts\CommentServiceInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CommentController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private readonly CommentServiceInterface $service) {}

    /**
     * @OA\Get(
     *     path="/comments",
     *     tags={"Comments"},
     *     summary="Получить список комментариев",
     *     @OA\Parameter(
     *         name="commentable_type",
     *         in="query",
     *         description="Тип объекта комментария",
     *         required=false,
     *         @OA\Schema(type="string", example="App\\Models\\Post")
     *     ),
     *     @OA\Parameter(
     *         name="commentable_id",
     *         in="query",
     *         description="ID объекта комментария",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Количество записей на странице",
     *         required=false,
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Список комментариев",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Comment")),
     *                 @OA\Property(property="links", type="object"),
     *                 @OA\Property(property="meta", type="object")
     *             )
     *         )
     *     )
     * )
     */
    public function index(): ResourceCollection
    {
        $filter = CommentFilterDto::fromRequest(request());
        $page = PageParams::fromRequest(request(), 15, 100);

        $comments = $this->service->paginate($filter, $page);

        return CommentResource::collection($comments);
    }

    /**
     * @OA\Post(
     *     path="/comments",
     *     tags={"Comments"},
     *     summary="Создать комментарий",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/CommentStoreRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Комментарий успешно создан",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/Comment")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Ошибка валидации")
     * )
     */
    public function store(CommentStoreRequest $request): CommentResource
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        $comment = $this->service->create($data);
        $comment->load(['user'])->loadCount('replies');

        return new CommentResource($comment);
    }

    /**
     * @OA\Get(
     *     path="/comments/{id}",
     *     tags={"Comments"},
     *     summary="Получить комментарий по ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID комментария",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Информация о комментарии",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/Comment")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Комментарий не найден")
     * )
     */
    public function show(Comment $comment): CommentResource
    {
        $comment->load(['user'])->loadCount('replies');
        return new CommentResource($comment);
    }

    /**
     * @OA\Put(
     *     path="/comments/{id}",
     *     tags={"Comments"},
     *     summary="Обновить комментарий",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID комментария",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/CommentUpdateRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Комментарий успешно обновлен",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/Comment")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Ошибка валидации"),
     *     @OA\Response(response=403, description="Нет прав для обновления комментария"),
     *     @OA\Response(response=404, description="Комментарий не найден")
     * )
     */
    public function update(CommentUpdateRequest $request, Comment $comment): CommentResource
    {
        $this->authorize('update', $comment);

        $comment = $this->service->update($comment, $request->validated());
        $comment->load(['user'])->loadCount('replies');

        return new CommentResource($comment);
    }

    /**
     * @OA\Delete(
     *     path="/comments/{id}",
     *     tags={"Comments"},
     *     summary="Удалить комментарий",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID комментария",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Комментарий успешно удален"
     *     ),
     *     @OA\Response(response=403, description="Нет прав для удаления комментария"),
     *     @OA\Response(response=404, description="Комментарий не найден")
     * )
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $this->service->delete($comment);
        return response()->noContent();
    }

    /**
     * @OA\Get(
     *     path="/comments/{userId}/to-active-posts",
     *     tags={"Comments"},
     *     summary="Получить комментарии пользователя к активным постам",
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         description="ID пользователя",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Количество записей на странице",
     *         required=false,
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Список комментариев пользователя к активным постам",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Comment")),
     *                 @OA\Property(property="links", type="object"),
     *                 @OA\Property(property="meta", type="object")
     *             )
     *         )
     *     )
     * )
     */
    public function userToActivePosts(int $userId): ResourceCollection
    {
        $comments = $this->service->getUserCommentsToActivePosts($userId, request('per_page', 15));
        return CommentResource::collection($comments);
    }

    /**
     * @OA\Get(
     *     path="/comments/mine",
     *     tags={"Comments"},
     *     summary="Получить мои комментарии",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Количество записей на странице",
     *         required=false,
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Список моих комментариев",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Comment")),
     *                 @OA\Property(property="links", type="object"),
     *                 @OA\Property(property="meta", type="object")
     *             )
     *         )
     *     )
     * )
     */
    public function mine(): ResourceCollection
    {
        $comments = $this->service->getCreatedByCurrentUser(request('per_page', 15));
        return CommentResource::collection($comments);
    }

    /**
     * @OA\Get(
     *     path="/posts/{postId}/comments",
     *     tags={"Comments"},
     *     summary="Получить комментарии к посту",
     *     @OA\Parameter(
     *         name="postId",
     *         in="path",
     *         description="ID поста",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Количество записей на странице",
     *         required=false,
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Список комментариев к посту",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Comment")),
     *                 @OA\Property(property="links", type="object"),
     *                 @OA\Property(property="meta", type="object")
     *             )
     *         )
     *     )
     * )
     */
    public function byPost(int $postId): ResourceCollection
    {
        $comments = $this->service->getByPost($postId, request('per_page', 15));
        return CommentResource::collection($comments);
    }

    /**
     * @OA\Get(
     *     path="/comments/{commentId}/replies",
     *     tags={"Comments"},
     *     summary="Получить ответы на комментарий",
     *     @OA\Parameter(
     *         name="commentId",
     *         in="path",
     *         description="ID комментария",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Количество записей на странице",
     *         required=false,
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Список ответов на комментарий",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Comment")),
     *                 @OA\Property(property="links", type="object"),
     *                 @OA\Property(property="meta", type="object")
     *             )
     *         )
     *     )
     * )
     */
    public function replies(int $commentId): ResourceCollection
    {
        $comments = $this->service->getReplies($commentId, request('per_page', 15));
        return CommentResource::collection($comments);
    }
}
