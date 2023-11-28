<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sponsor extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function getImageAttribute($value)
    {
        if (trim($value)) {
            return url('storage/'.$value);
        }

        return null;
    }
}
