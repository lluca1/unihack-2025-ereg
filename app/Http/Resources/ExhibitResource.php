<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExhibitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'exposition_id' => $this->exposition_id,
            'title' => $this->title,
            'description' => $this->description,
            'media_type' => $this->media_type,
            'media_path' => $this->media_path,
            'thumbnail_path' => $this->thumbnail_path,
            'mime_type' => $this->mime_type,
            'position' => $this->position,
            'created_at' => optional($this->created_at)->toIso8601String(),
            'updated_at' => optional($this->updated_at)->toIso8601String(),
        ];
    }
}
