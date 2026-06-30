<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ToDoTaskResource extends JsonResource
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
            'title' => $this->title,
            'status' => $this->is_complete ? "complete" : "incomplete",
            'order' => $this->ordered,
            'parent_id' => $this->parent_id,
            'subTask' => ToDoTaskResource::collection($this->whenLoaded('subTask'))
        ];
    }
}
