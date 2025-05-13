<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required',
            'old_price' => 'nullable',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'translations' => 'required|array',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.description' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'image.required' => __('product.image_required'),
            'image.image' => __('product.image_image'),
            'image.mimes' => __('product.image_mimes'),
            'image.max' => __('product.image_max'),
            'price.required' => __('product.price_required'),
            'categories.required' => __('product.categories_required'),
            'categories.array' => __('product.categories_array'),
            'categories.*.exists' => __('product.categories_exists'),
            'translations.required' => __('product.translations_required'),
            'translations.array' => __('product.translations_array'),
            'translations.*.name.required' => __('product.translations_name_required'),
            'translations.*.name.string' => __('product.translations_name_string'),
            'translations.*.name.max' => __('product.translations_name_max'),
            'translations.*.description.required' => __('product.translations_description_max'),
            'translations.*.description.string' => __('product.translations_description_string'),
        ];
    }
}
