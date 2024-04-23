<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $owner_type = $this->mediable_type;
        $owner_type = strtolower(substr($owner_type, strrpos($owner_type, '\\') + 1));

        return [
            'id' => $this->id,
            'mime_type' => $this->mime_type,
            'owner_id' => $this->mediable_id,
            'owner_type' => $owner_type,
            'link' => $this->link,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
