<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClubSocial extends Model
{
    use HasFactory;

    protected $fillable = [
        'service',
        'club_id',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function club() {
        return $this->belongsTo(Club::class);
    }

}
