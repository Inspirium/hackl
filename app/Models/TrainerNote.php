<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainerNote extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'is_public' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'videos' => 'array',
    ];

    public function team() {
        return $this->belongsTo(Team::class);
    }

    public function club() {
        return $this->belongsTo(Club::class);
    }

    public function trainer() {
        return $this->belongsTo(User::class);
    }

    public function getVideosAttribute($value)
    {
        if ($value) {
            $videos = json_decode($value);
            $out = [];
            foreach ($videos as $video) {
                if (strpos($video, 'youtube') !== false) {
                    $video = str_replace('watch?v=', 'embed/', $video);
                }
                if (strpos($video,'https://vimeo.com') !== false) {
                    $video = str_replace('https://vimeo.com', 'https://player.vimeo.com/video', $video);
                }
                $out[] = $video;
            }
            return $out;
        }

        return [];
    }
}
