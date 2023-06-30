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
            'email_verified_at' => Arr::get($data, 'email_verified_at'),
            'is_email_verified' => Arr::get($data, 'is_email_verified'),
            'phone_number_verified_at' => Arr::get($data, 'phone_number_verified_at'),
            'is_phone_number_verified' => false,
            'connections_count' => Arr::get($data, 'network_users_count') ?? 0,
            'pending_invitations_count' => Arr::get($data, 'pending_invitations_count') ?? 0,
            'request_invitations_count' => Arr::get($data, 'request_invitations_count') ?? 0,
            'mutuals_count' => Arr::get($data, 'mutuals_count') ?? 0,
            'socials' => SocialResource::collection($this->whenLoaded('socials')),
            'broker_license' => new BrokerLicenseResource($this->whenLoaded('brokerLicense')),
            'created_at' => Arr::get($data, 'created_at')
        ];

        if (Arr::has($data, 'token')) Arr::set($response, 'token', Arr::get($data, 'token'));

        return $response;
    }
}
