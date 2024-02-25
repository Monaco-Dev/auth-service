<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class SocialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        $data = Arr::except($data, [
            'created_at',
            'updated_at'
        ]);

        Arr::set($data, 'user', new UserResource($this->whenLoaded('user')));

        return $data;
    }
}
