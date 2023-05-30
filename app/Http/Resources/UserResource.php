<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        $response = [
            'id' => Arr::get($data, 'id'),
            'username' => Arr::get($data, 'username'),
            'email' => Arr::get($data, 'email'),
            'phone_number' => Arr::get($data, 'phone_number'),
            'first_name' => Arr::get($data, 'first_name'),
            'last_name' => Arr::get($data, 'last_name'),
            'full_name' => Arr::get($data, 'first_name') . ' ' . Arr::get($data, 'last_name'),
            'broker_license' => new BrokerLicenseResource($this->whenLoaded('brokerLicense')),
            'connections' => ConnectionResource::collection($this->whenLoaded('connections')),
            'socials' => SocialResource::collection($this->whenLoaded('socials'))
        ];

        if (Arr::has($data, 'token')) Arr::set($response, 'token', Arr::get($data, 'token'));

        return $response;
    }
}
