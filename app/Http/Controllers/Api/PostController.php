<?php

namespace App\Http\Controllers\Api;

use App\DTO\Common\PageParams;
use App\DTO\Post\PostCreateDto;
use App\DTO\Post\PostUpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostStoreRequest;
use App\Http\Requests\Post\PostUpdateRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\Contracts\PostServiceInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PostController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private readonly PostServiceInterface $service) {}

    /**
     * @OA\Get(
     *     path="/posts",
     *     tags={"Posts"},
     *     summary="Получить список постов",
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Статус поста",
     *         required=false,
     *         @OA\Schema(type="string", enum={"active", "inactive"}, example="active")
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="ID автора поста",
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
     *         description="Список постов",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Post")),
     *                 @OA\Property(property="links", type="object"),
     *                 @OA\Property(property="meta", type="object")
     *             )
     *         )
     *     )
     * )
     */
    public function index(): ResourceCollection
    {
        $filters = request()->only(['status','user_id']);
        $page = PageParams::fromRequest(request(), 15, 100);
        
        $posts = $this->service->paginate($filters, $page);
        return PostResource::collection($posts);
    }

    /**
     * @OA\Post(
     *     path="/posts",
     *     tags={"Posts"},
     *     summary="Создать пост",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/PostStoreRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Пост успешно создан",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/Post")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Ошибка валидации")
     * )
     */
    public function store(PostStoreRequest $request): PostResource
    {
        $post = $this->service->create(PostCreateDto::fromRequest($request));
        $post->loadCount('comments')->load('user');

        return new PostResource($post);
    }

    /**
     * @OA\Get(
     *     path="/posts/{id}",
     *     tags={"Posts"},
     *     summary="Получить пост по ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID поста",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Информация о посте",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/Post")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Пост не найден")
     * )
     */
    public function show(Post $post): PostResource
    {
        $post->loadCount('comments')->load('user');
        return new PostResource($post);
    }

    /**
     * @OA\Put(
     *     path="/posts/{id}",
     *     tags={"Posts"},
     *     summary="Обновить пост",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID поста",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/PostUpdateRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Пост успешно обновлен",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/Post")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Ошибка валидации"),
     *     @OA\Response(response=403, description="Нет прав для обновления поста"),
     *     @OA\Response(response=404, description="Пост не найден")
     * )
     */
    public function update(PostUpdateRequest $request, Post $post): PostResource
    {
        $this->authorize('update', $post);

        $post = $this->service->update($post, PostUpdateDto::fromRequest($request));
        $post->loadCount('comments')->load('user');

        return new PostResource($post);
    }

    /**
     * @OA\Delete(
     *     path="/posts/{id}",
     *     tags={"Posts"},
     *     summary="Удалить пост",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID поста",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Пост успешно удален"
     *     ),
     *     @OA\Response(response=403, description="Нет прав для удаления поста"),
     *     @OA\Response(response=404, description="Пост не найден")
     * )
     */
    public function destroy(PostUpdateRequest $request, Post $post)
    {
        $this->authorize('delete', $post);

        $this->service->delete($post);
        return response()->noContent();
    }

    /**
     * @OA\Get(
     *     path="/posts/{userId}/active",
     *     tags={"Posts"},
     *     summary="Получить активные посты пользователя",
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
     *         description="Список активных постов пользователя",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Post")),
     *                 @OA\Property(property="links", type="object"),
     *                 @OA\Property(property="meta", type="object")
     *             )
     *         )
     *     )
     * )
     */
    public function userActive(int $userId): ResourceCollection
    {
        $page = PageParams::fromRequest(request(), 15, 100);
        $posts = $this->service->getActiveByUser($userId, $page);
        return PostResource::collection($posts);
    }

    /**
     * @OA\Get(
     *     path="/posts/mine",
     *     tags={"Posts"},
     *     summary="Получить мои посты",
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
     *         description="Список моих постов",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Post")),
     *                 @OA\Property(property="links", type="object"),
     *                 @OA\Property(property="meta", type="object")
     *             )
     *         )
     *     )
     * )
     */
    public function mine(): ResourceCollection
    {
        $page = PageParams::fromRequest(request(), 15, 100);
        $posts = $this->service->getCreatedByCurrentUser($page);
        return PostResource::collection($posts);
    }
}
