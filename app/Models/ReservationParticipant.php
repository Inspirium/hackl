<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReservationParticipant extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'users_reservations';

    public function reservation()
    {
        return $this->belongsTo(Reservation::class)->withTrashed();
    }

    public function player()
    {
        return $this->belongsTo(Team::class, 'user_id')->withTrashed();
    }
}
