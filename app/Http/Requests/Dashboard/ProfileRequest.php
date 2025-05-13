<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
        $isAmbassador = !is_null(auth()->user()->ambassador_uuid);

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . auth()->user()->id
            ],
            'surname' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'max:255'],
            'date_of_birth' => [$isAmbassador ? 'required' : 'nullable', 'date', 'after:' . now()->subYears(100)->format('Y-m-d')],
            'address' => [$isAmbassador ? 'required' : 'nullable', 'string', 'max:255'],
            'country_id' => [$isAmbassador ? 'required' : 'nullable', 'exists:countries,id'],
            'city' => [$isAmbassador ? 'required' : 'nullable', 'string', 'max:255'],
        ];
    }

}
