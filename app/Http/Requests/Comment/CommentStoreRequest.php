<?php

namespace App\Http\Requests\Comment;

use App\Models\Comment;
use App\Models\Post;
use App\Rules\PolymorphicExists;
use Illuminate\Foundation\Http\FormRequest;

class CommentStoreRequest extends FormRequest
{
    private PolymorphicExists $poly;

    public function __construct()
    {
        $this->poly = new PolymorphicExists([
            'post' => Post::class,
            'comment' => Comment::class,
        ]);
        parent::__construct();
    }

    public function rules(): array
    {
        return [
            'body' => ['required', 'string'],
            'commentable_type' => ['required', 'string', $this->poly],
            'commentable_id' => ['required', 'integer'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $data = $this->validated();
            if (isset($data['commentable_type'], $data['commentable_id'])) {
                $this->poly->checkId($data['commentable_type'], $data['commentable_id'], function ($msg) use ($v) {
                    $v->errors()->add('commentable_id', $msg);
                });
            }
        });
    }
}
