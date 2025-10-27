<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
{
    public function rules(): array
    {
        $postId = $this->route('id');

        return [
            'title' => ['sometimes', 'required', Rule::unique('posts')->ignore($postId), 'max:255'],
            'content' => ['sometimes', 'required'],
            'category_id' => ['sometimes', 'required', 'exists:categories,id'],
        ];
    }
}
