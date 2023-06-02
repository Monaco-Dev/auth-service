<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class ConnectionResource extends JsonResource
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
            'username' => Arr::get($data, 'username'),
            'email' => Arr::get($data, 'email'),
            'phone_number' => Arr::get($data, 'phone_number'),
            'first_name' => Arr::get($data, 'first_name'),
            'last_name' => Arr::get($data, 'last_name'),
            'full_name' => Arr::get($data, 'first_name') . ' ' . Arr::get($data, 'last_name'),
            'is_email_verified' => !!Arr::get($data, 'email_verified_at'),
            'is_phone_number_verified' => !!Arr::get($data, 'phone_number_verified_at'),
            'socials' => SocialResource::collection($this->whenLoaded('socials')),
            'broker_license' => new BrokerLicenseResource($this->whenLoaded('brokerLicense'))
        ];
    }
}
