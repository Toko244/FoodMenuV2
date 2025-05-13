<?php

namespace App\Http\Resources\Company;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'step1' => [
                'country_id' => $this->country_id,
                'default_language_id' => $this->default_language_id,
                'logo' => $this->logo ? url($this->logo) : null,
                'email' => $this->email,
                'phone' => $this->phone,
                'zip' => $this->zip,
                'can_edit' => $this->ambassadors->isNotEmpty() ? $this->ambassadors->first()->pivot->can_edit : null,
                'searchable' => $this->searchable,
                'sub_domain' => $this->sub_domain,
                'languages' => $this->languages->pluck(['id']),
            ],
            'step2' => [
                'translations' => $this->translations->mapWithKeys(function ($translation) {
                    return [
                        $translation->language_id => new CompanyTranslationsResource($translation),
                    ];
                })
            ],
            'step3' => [
                'facebook' => $this->details->facebook,
                'twitter' => $this->details->twitter,
                'instagram' => $this->details->instagram,
                'linkedIn' => $this->details->linkedIn,
                'tiktok' => $this->details->tiktok,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
