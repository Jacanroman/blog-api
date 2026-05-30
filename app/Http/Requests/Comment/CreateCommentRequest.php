<?php

namespace App\Http\Requests\Comment;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isAuthenticated = auth('sanctum')->check();

       return [
            'recipe_id'   => ['required', 'integer', 'exists:recipes,id'],
            'parent_id'   => ['nullable', 'integer', 'exists:comments,id'],
            'body'        => ['required', 'string', 'min:3', 'max:1000'],
            // Guest-only fields (ignored when authenticated)
            'guest_name'  => [$isAuthenticated ? 'nullable' : 'required', 'string', 'max:100'],
            'guest_email' => ['nullable', 'email', 'max:255'],
        ];
    }
}
