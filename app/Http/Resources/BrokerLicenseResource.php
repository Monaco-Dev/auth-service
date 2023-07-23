<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class BrokerLicenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        return [
            'id' => Arr::get($data, 'id'),
            'is_license_verified' => Arr::get($data, 'is_license_verified'),
            'is_license_expired' => Arr::get($data, 'is_license_expired'),
            'license_number' => Arr::get($data, 'license_number'),
            'expiration_date' => Arr::get($data, 'expiration_date'),
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
