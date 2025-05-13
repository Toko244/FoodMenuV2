<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class TagRequest extends FormRequest
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
            // Tag Data -----------------------------------------------------------
            'icon' => 'nullable|string',
            'color' => 'nullable|string',

            // Tag Translations ---------------------------------------------------
            'translations' => 'required|array',
            'translations.*.language_id' => 'required|exists:languages,id',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.description' => 'required|string',

            // Tag Languages ------------------------------------------------------
            'languages' => 'required|array',
            'languages.*' => 'required|exists:languages,id',
        ];
    }
}
