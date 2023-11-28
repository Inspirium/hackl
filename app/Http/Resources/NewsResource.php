<?php

namespace App\Http\Resources;

use App\Models\News;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class NewsResource
 *
 * @mixin News
 */
class NewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'name' => $this->name,
            'content' => $this->content,
            'image' => $this->image,
            'created_at' => $this->created_at,
            'author' => $this->author,
            'club_id' => $this->club_id,
            'club' => $this->whenLoaded('club'),
        ];
    }
}
