<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReservationCategory extends Model
{
    use HasFactory, SoftDeletes;


    protected $guarded = [];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function images() {
        return $this->morphToMany(Media::class, 'imageable', 'media_imageable')->withPivot(['main']);
    }

    public function getImageAttribute() {
        return $this->images()->wherePivot('main', 1)->first();
    }
}
