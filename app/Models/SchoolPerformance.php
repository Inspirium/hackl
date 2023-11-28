<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolPerformance extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'data' => 'array'
    ];

    public function player() {
        return $this->belongsTo(User::class, 'player_id');
    }

    public function trainer() {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function schoolGroup(){
        return $this->belongsTo(SchoolGroup::class);
    }

    public function reservation(){
        return $this->belongsTo(Reservation::class);
    }
}
