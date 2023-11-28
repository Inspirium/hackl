<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;

/**
 * App\Models\Membership
 *
 * @property int $id
 * @property string $name
 * @property int $club_id
 * @property int $max_reservation_span
 * @property int $is_reservation_cancelable
 * @property int|null $reservation_cancelable
 * @property int|null $has_discount
 * @property int|null $discount_type
 * @property float|null $discount_amount
 * @property float|null $price
 * @property string|null $description
 * @property int $public
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $basic
 * @property-read \App\Models\Club $club
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Payment[] $payments
 * @property-read int|null $payments_count
 * @method static \Illuminate\Database\Eloquent\Builder|Membership newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Membership newQuery()
 * @method static \Illuminate\Database\Query\Builder|Membership onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Membership query()
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereBasic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereClubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereHasDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereIsReservationCancelable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereMaxReservationSpan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership wherePublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereReservationCancelable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Membership whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Membership withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Membership withoutTrashed()
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 */
class Membership extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use SoftDeletes, Auditable;

    protected $guarded = [];

    protected $casts = [
        'data' => 'array',
        'reservation_prepayment' => 'boolean',
        'max_reservation_per_period' => 'boolean',
        'basic' => 'boolean',
        'subscription' => 'array',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'type');
    }

    public function users() {
        return $this->belongsToMany(User::class, 'user_membership');
    }

    public function user_memberships() {
        return $this->hasMany(UserMembership::class);
    }
}
