<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Attendance
 *
 * @property int $id
 * @property int $reservation_id
 * @property int $user_id
 * @property int $school_group_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $player
 * @property-read \App\Models\Reservation $reservation
 * @property-read \App\Models\SchoolGroup $schoolGroup
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance query()
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereReservationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereSchoolGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereUserId($value)
 * @mixin \Eloquent
 */
class Attendance extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function player()
    {
        return $this->belongsTo(User::class);
    }

    public function schoolGroup()
    {
        return $this->belongsTo(SchoolGroup::class);
    }
}
