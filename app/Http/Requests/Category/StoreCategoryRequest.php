<?php

namespace App\Http\Requests\Category;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Override;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    #[Override]
    protected function prepareForValidation()
    {
        $this->merge([
            'name' => trim($this->name),
            'slug' => trim($this->slug),
            'description' => trim($this->description)
        ]); 
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //we don't validate the slug due it is generated with the name so we need to check the name
            'name' => ['required','string','max:255','unique:categories,name'],
            'description' => ['nullable','string'],
        ];
    }
}
