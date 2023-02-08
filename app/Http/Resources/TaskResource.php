<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => (string)$this->id,
            'title' => $this->title,
            'description' => $this->description,
            'completed' => (bool)$this->completed,
            'dueDate' => $this->due_date,
            'createdAt' => (string) $this->created_at,
            'updatedAt' => (string) $this->updated_at,
        ];
    }
}
