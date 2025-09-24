<?php

namespace Modules\News\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateNewsRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Admin middleware handles authorization
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:news,slug',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'meta_description' => 'nullable|string|max:160',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'source' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published',
            'featured' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'The title field is required.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'slug.unique' => 'This slug is already taken.',
            'content.required' => 'The content field is required.',
            'excerpt.max' => 'The excerpt may not be greater than 500 characters.',
            'meta_description.max' => 'The meta description may not be greater than 160 characters.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, webp.',
            'image.max' => 'The image may not be greater than 2MB.',
            'status.required' => 'Please select a status.',
            'status.in' => 'Status must be either draft or published.',
            'published_at.date' => 'Please enter a valid publication date.',
        ];
    }

    public function prepareForValidation()
    {
        // Convert featured checkbox to boolean
        if ($this->has('featured')) {
            $this->merge([
                'featured' => $this->boolean('featured')
            ]);
        }
    }
}
