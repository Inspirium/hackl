<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Wallet
 *
 * @property int $id
 * @property int $owner_id
 * @property int|null $club_id
 * @property string|null $name
 * @property float|null $amount
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Club|null $club
 * @property-read \App\Models\User $owner
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WalletTransaction[] $transactions
 * @property-read int|null $transactions_count
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet amountGreaterThan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet amountLessThan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet query()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereClubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Wallet extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class)->orderBy('created_at', 'desc')->limit(50);
    }

    public function updateAmount($amount)
    {
        $this->amount += $amount;
        $this->save();
    }

    public function scopeAmountGreaterThan($query, $value) {
        return $query->where('amount', '>=', $value);
    }

    public function scopeAmountLessThan($query, $value) {
        return $query->where('amount', '<=', $value);
    }

    public function payments() {
        return $this->morphMany(Payment::class, 'type');
    }
}
