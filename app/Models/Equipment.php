<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'data' => 'array',
    ];

    public function player() {
        return $this->belongsTo(User::class);
    }

    public function getImageAttribute($value) {
        if (trim($value) && \Storage::disk('public')->exists($value)) {
            return url('storage/'.$value);
        }

        return $value;
    }
}
