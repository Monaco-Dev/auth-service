<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

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

        $fields = [
            'id',
            'first_name',
            'last_name',
            'is_email_verified',
            'is_deactivated',
            'full_name',
            'url',
            'connections_count',
            'followers_count',
            'is_verified'
        ];

        if (Arr::get($data, 'id') == optional(Auth::user())->id) {
            $fields = array_merge($fields, [
                'email',
                'phone_number',
                'incoming_invites_count',
                'outgoing_invites_count',
                'following_count',
                'token',
            ]);
        } else {
            $fields = array_merge($fields, [
                'mutuals_count',
                'is_incoming_invite',
                'is_outgoing_invite',
                'is_connection',
                'is_following',
                'is_follower',
            ]);

            if (Arr::get($data, 'is_connection')) {
                $fields = array_merge($fields, [
                    'email',
                    'phone_number',
                ]);
            }
        }

        $data = Arr::only($data, $fields);

        Arr::set($data, 'broker_license', new BrokerLicenseResource($this->whenLoaded('brokerLicense')));
        Arr::set($data, 'slugs', SlugResource::collection($this->whenLoaded('slugs')));

        return $data;
    }
}
