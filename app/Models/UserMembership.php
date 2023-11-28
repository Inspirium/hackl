<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\UserMembership
 *
 * @property int $id
 * @property int $user_id
 * @property int $membership_id
 * @property float|null $price
 * @property int|null $payment_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $expires_at
 * @property-read \App\Models\Membership $membership
 * @property-read \App\Models\Payment|null $payment
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserMembership newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserMembership newQuery()
 * @method static \Illuminate\Database\Query\Builder|UserMembership onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|UserMembership query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserMembership whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMembership whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMembership whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMembership whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMembership whereMembershipId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMembership wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMembership wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMembership whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMembership whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|UserMembership withTrashed()
 * @method static \Illuminate\Database\Query\Builder|UserMembership withoutTrashed()
 * @mixin \Eloquent
 */
class UserMembership extends Pivot
{
    use SoftDeletes;

    protected $table = 'user_membership';

    protected $guarded = [];

    protected $with = ['membership'];

    protected $casts = [
        'expires_at' => 'timestamp'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function invoices() {
        return $this->morphMany(InvoiceItem::class, 'invoiceable');
    }
}
