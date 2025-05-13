<?php

namespace App\Http\Resources\Company;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyTranslationsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'language_id' => $this->language_id,
            'name' => $this->name,
            'description' => $this->description,
            'state' => $this->state,
            'city' => $this->city,
            'address' => $this->address,
        ];
    }
}
