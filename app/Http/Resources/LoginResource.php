<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Config;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        return [
            'token_type' => 'Bearer',
            'expires_in' => Config::get('sanctum.expiration', 60) * 60,
            'access_token' => $this->resource->createToken(env('USER_TOKEN'), ['create', 'read', 'update', 'delete'])->plainTextToken,
        ];
    }
}
