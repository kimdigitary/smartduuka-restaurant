<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class TenantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            "id"     => $this->id,
            "name"   => $this->name,
            "phone"  => $this->phone,
            "email"  => $this->email,
            "tagline" => $this->tagline,
            "status" => $this->status,
            "website" => $this->website,
            "address" => $this->address,
            "username" => $this->username
        ];
    }
}
