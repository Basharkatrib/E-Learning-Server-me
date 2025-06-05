<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);
        return [
            'id' => $this->id,
            "name" => [
                "en" => $this->name,
                "ar" => $this->getTranslation("name", "ar"),
            ],
            "description" => [
                "en" => $this->description,
                "ar" => $this->getTranslation("description", "ar"),
            ],
            'parent_id' => $this->parent_id,
            'children' => CategoryResource::collection($this->whenLoaded('children')),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
