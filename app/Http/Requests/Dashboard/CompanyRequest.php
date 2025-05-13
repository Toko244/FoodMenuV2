<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CompanyRequest extends FormRequest
{
    protected $company_id;

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
        $this->company_id = $this->route('company') ? $this->route('company')->id : null;

        return [
            // Company Data -----------------------------------------------------------
            'step1.country_id' => 'required|exists:countries,id',
            'step1.default_language_id' => 'required|exists:languages,id',
            'step1.logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'step1.email' => 'required|email',
            'step1.phone' => 'required|string|max:255',
            'step1.zip' => 'nullable|string|max:255',
            'step1.can_edit' => 'nullable|boolean',
            'step1.searchable' => 'nullable|boolean',
            'step1.sub_domain' => ['required', 'string', 'regex:/^[a-zA-Z0-9-]+$/', 'unique:companies,sub_domain,' . $this->company_id],


            // Company Details --------------------------------------------------------
            'step3.facebook' => 'nullable|string|max:255',
            'step3.twitter' => 'nullable|string|max:255',
            'step3.instagram' => 'nullable|string|max:255',
            'step3.linkedIn' => 'nullable|string|max:255',
            'step3.tiktok' => 'nullable|string|max:255',
            'step3.latitude' => 'nullable|string|max:255',
            'step3.longitude' => 'nullable|string|max:255',

            // Company Translations ---------------------------------------------------
            'step2.translations' => 'required|array',
            'step2.translations.*.language_id' => 'required|exists:languages,id',
            'step2.translations.*.name' => 'required|string|max:255',
            'step2.translations.*.description' => 'required|string',
            'step2.translations.*.state' => 'required|string|max:255',
            'step2.translations.*.city' => 'required|string|max:255',
            'step2.translations.*.address' => 'required|string|max:255',

            // Company Languages
            'step1.languages' => 'required|array',
            'step1.languages.*' => 'required|exists:languages,id',

            // Venue Categories ------------------------------------------------------
            // 'venue_categories' => 'required|array',
            // 'venue_categories.*' => 'exists:venue_categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            // Company Data -----------------------------------------------------------
            'step1.country_id.required' => __('company.country_id_required'),
            'step1.country_id.exists' => __('company.country_id_exists'),
            'step1.default_language_id.required' => __('company.default_language_id_required'),
            'step1.default_language_id.exists' => __('company.default_language_id_exists'),
            'step1.logo.image' => __('company.logo_image'),
            'step1.email.required' => __('company.email_required'),
            'step1.email.email' => __('company.email_valid'),
            'step1.phone.required' => __('company.phone_required'),
            'step1.phone.string' => __('company.phone_string'),
            'step1.phone.max' => __('company.phone_max'),
            'step1.zip.string' => __('company.zip_string'),
            'step1.zip.max' => __('company.zip_max'),
            'step1.can_edit.boolean' => __('company.can_edit_boolean'),
            'step1.searchable.boolean' => __('company.searchable_boolean'),
            'step1.sub_domain.required' => __('company.sub_domain_required'),
            'step1.sub_domain.string' => __('company.sub_domain_string'),
            'step1.sub_domain.regex' => __('company.sub_domain_regex'),
            'step1.sub_domain.unique' => __('company.sub_domain_unique'),
            'step1.logo.mimes' => __('company.logo_mime'),

            // Company Details --------------------------------------------------------
            'step3.facebook.string' => __('company.facebook_string'),
            'step3.facebook.max' => __('company.facebook_max'),
            'step3.twitter.string' => __('company.twitter_string'),
            'step3.twitter.max' => __('company.twitter_max'),
            'step3.instagram.string' => __('company.instagram_string'),
            'step3.instagram.max' => __('company.instagram_max'),
            'step3.linkedIn.string' => __('company.linkedIn_string'),
            'step3.linkedIn.max' => __('company.linkedIn_max'),
            'step3.tiktok.string' => __('company.tiktok_string'),
            'step3.tiktok.max' => __('company.tiktok_max'),
            'step3.latitude.string' => __('company.latitude_string'),
            'step3.latitude.max' => __('company.latitude_max'),
            'step3.longitude.string' => __('company.longitude_string'),
            'step3.longitude.max' => __('company.longitude_max'),

            // Company Translations ---------------------------------------------------
            'step2.translations.required' => __('company.translations_required'),
            'step2.translations.array' => __('company.translations_array'),
            'step2.translations.*.language_id.required' => __('company.translations_language_id_required'),
            'step2.translations.*.language_id.exists' => __('company.translations_language_id_exists'),
            'step2.translations.*.name.required' => __('company.translations_name_required'),
            'step2.translations.*.name.string' => __('company.translations_name_string'),
            'step2.translations.*.name.max' => __('company.translations_name_max'),
            'step2.translations.*.description.required' => __('company.translations_description_required'),
            'step2.translations.*.description.string' => __('company.translations_description_string'),
            'step2.translations.*.state.required' => __('company.translations_state_required'),
            'step2.translations.*.state.string' => __('company.translations_state_string'),
            'step2.translations.*.state.max' => __('company.translations_state_max'),
            'step2.translations.*.city.required' => __('company.translations_city_required'),
            'step2.translations.*.city.string' => __('company.translations_city_string'),
            'step2.translations.*.city.max' => __('company.translations_city_max'),
            'step2.translations.*.address.required' => __('company.translations_address_required'),
            'step2.translations.*.address.string' => __('company.translations_address_string'),
            'step2.translations.*.address.max' => __('company.translations_address_max'),

            // Company Languages
            'step1.languages.required' => __('company.languages_required'),
            'step1.languages.array' => __('company.languages_array'),
            'step1.languages.*.required' => __('company.languages_language_id_required'),
            'step1.languages.*.exists' => __('company.languages_language_id_exists'),

            // Venue Categories ------------------------------------------------------
            // 'venue_categories.required' => __('company.venue_categories_required'),
            // 'venue_categories.array' => __('company.venue_categories_array'),
            // 'venue_categories.*.exists' => __('company.venue_categories_exists'),
        ];
    }
}
