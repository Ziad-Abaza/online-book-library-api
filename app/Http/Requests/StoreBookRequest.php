<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    public function authorize()
    {
        return false; 
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publish' => 'required|string|max:255',
            'file' => 'required',
            'cover_image' => 'required',
            'size' => 'nullable|max:50',
            'number_pages' => 'nullable|integer|min:1',
            'published_at' => 'required|date',
            'is_downloadable' => 'required|boolean',
            'category_id' => 'required|exists:categories,id',
        ];
    }
}
