<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\ClubMembership
 *
 * @property int $club_id
 * @property int $player_id
 * @property int|null $rank
 * @property float|null $power
 * @property string|null $status
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $active_membership
 * @method static \Illuminate\Database\Eloquent\Builder|ClubMembership newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClubMembership newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClubMembership query()
 * @method static \Illuminate\Database\Eloquent\Builder|ClubMembership whereActiveMembership($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClubMembership whereClubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClubMembership whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClubMembership whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClubMembership whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClubMembership wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClubMembership wherePower($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClubMembership whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClubMembership whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClubMembership whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ClubMembership extends Pivot
{
    protected $table = 'club_user';

    public $incrementing = true;

    protected $casts = [
        'messages' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'cashier' => 'boolean',
    ];

    protected static function booted()
    {
        static::created(function (ClubMembership $membership) {
            $m = $membership->club->memberships()->where('basic', 1)->first();
            if ($m) {
                $membership->player->memberships()->attach($m->id);
            } else {

            }
        });
    }

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function player()
    {
        return $this->belongsTo(User::class, 'player_id');
    }

    public function active_membership()
    {
        return $this->belongsTo(UserMembership::class, 'active_membership');
    }
}
