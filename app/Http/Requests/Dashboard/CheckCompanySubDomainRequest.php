<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class CheckCompanySubDomainRequest extends FormRequest
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
            'company_id' => 'nullable|exists:companies,id',
            'sub_domain' => [
                'required',
                'string',
                'regex:/^[a-zA-Z0-9-]+$/',
                'unique:companies,sub_domain,' . $this->company_id,
            ],
        ];
    }

    public function messages()
    {
        return [
            "sub_domain.unique" => __('company.company_sub_domain_taken'),
            'sub_domain.regex' => __('company.sub_domain_invalid_format'),
        ];
    }
}
