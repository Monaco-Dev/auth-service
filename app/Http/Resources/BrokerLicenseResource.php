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
            'license_number' => Arr::get($data, 'license_number'),
            'user' => new UserResource($this->whenLoaded('user'))
        ];
    }
}
