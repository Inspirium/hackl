<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\WalletTransaction
 *
 * @property int $id
 * @property int $wallet_id
 * @property string|null $note
 * @property float $amount
 * @property int $user_id
 * @property int|null $storno
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Wallet $wallet
 * @method static Builder|WalletTransaction amountGreaterThan($value)
 * @method static Builder|WalletTransaction amountLessThan($value)
 * @method static Builder|WalletTransaction createdBetween($value1, $value2 = null)
 * @method static Builder|WalletTransaction newModelQuery()
 * @method static Builder|WalletTransaction newQuery()
 * @method static Builder|WalletTransaction query()
 * @method static Builder|WalletTransaction whereAmount($value)
 * @method static Builder|WalletTransaction whereCreatedAt($value)
 * @method static Builder|WalletTransaction whereDeletedAt($value)
 * @method static Builder|WalletTransaction whereId($value)
 * @method static Builder|WalletTransaction whereNote($value)
 * @method static Builder|WalletTransaction whereStorno($value)
 * @method static Builder|WalletTransaction whereUpdatedAt($value)
 * @method static Builder|WalletTransaction whereUserId($value)
 * @method static Builder|WalletTransaction whereWalletId($value)
 * @mixin \Eloquent
 */
class WalletTransaction extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($transaction) {
            $transaction->wallet->updateAmount($transaction->amount);
        });
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeAmountGreaterThan(Builder $builder, $value) {
        return $builder->where('amount', '>=', $value);
    }

    public function scopeAmountLessThan(Builder $builder, $value) {
        return $builder->where('amount', '<=', $value);
    }

    public function scopeCreatedBetween(Builder $builder, $value1, $value2 = null) {
        if (!$value2) {
            $value2 = 'now';
        }
        return $builder->whereBetween('created_at',  [ Carbon::parse($value1), Carbon::parse($value2) ]);
    }
}
