<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'author' => 'sometimes|required|string|max:255',
            'publish' => 'sometimes|required|string|max:255',
            'file'=> 'sometimes|required',
            'cover_image' => 'sometimes|required',
            'size' => 'sometimes|nullable|max:50',
            'number_pages' => 'sometimes|nullable|integer|min:1',
            'published_at' => 'sometimes|required|date',
            'is_downloadable' => 'sometimes|required|boolean',
            'category_id' => 'sometimes|required|exists:categories,id',
        ];
    }
}
