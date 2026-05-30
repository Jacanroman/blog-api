<?php

namespace App\Http\Requests\Recipe;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRecipeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => ['sometimes', 'string', 'max:255',
                              Rule::unique('recipes', 'title')->ignore($this->route('recipe'))],
            'excerpt'     => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'ingredients' => ['nullable', 'array'],
            'ingredients.*.name'   => ['required_with:ingredients', 'string'],
            'ingredients.*.amount' => ['nullable', 'string'],
            'ingredients.*.unit'   => ['nullable', 'string'],
            'steps'               => ['nullable', 'array'],
            'steps.*.order'       => ['required_with:steps', 'integer'],
            'steps.*.instruction' => ['required_with:steps', 'string'],
            'steps.*.tip'         => ['nullable', 'string'],
            'prep_time'   => ['nullable', 'integer', 'min:1'],
            'cook_time'   => ['nullable', 'integer', 'min:1'],
            'servings'    => ['nullable', 'integer', 'min:1'],
            'difficulty'  => ['nullable', 'in:easy,medium,hard'],
            'country'     => ['nullable', 'string', 'max:100'],
            'region'      => ['nullable', 'string', 'max:100'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'status'      => ['nullable', 'in:draft,published,archived'],
            'tags'        => ['nullable', 'array'],
            'tags.*'      => ['integer', 'exists:tags,id'],
        ];
    }
}