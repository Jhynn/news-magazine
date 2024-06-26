<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'middle_name' => $this->middle_name,
            'surname' => $this->surname,
            'email' => $this->email,
            'username' => $this->username,
            'bio' => $this->bio,
            'status' => $this->status,
            'medias' => MediaResource::collection($this->whenLoaded('medias')),
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'articles' => ArticleResource::collection($this->whenLoaded('articles')),
            'topics' => TopicResource::collection($this->whenLoaded('topics')),
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
