<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Payment
 *
 * @property int $id
 * @property int $user_id
 * @property int $club_id
 * @property float|null $amount
 * @property int|null $receiver_id
 * @property int|null $type_id
 * @property string|null $type_type
 * @property int|null $from_credit
 * @property string|null $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Club $club
 * @property-read \App\Models\User|null $receiver
 * @property-read Model|\Eloquent $type
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newQuery()
 * @method static \Illuminate\Database\Query\Builder|Payment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereClubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereFromCredit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereReceiverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereTypeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Payment withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Payment withoutTrashed()
 * @mixin \Eloquent
 * @property int|null $wallet_id
 * @property-read \App\Models\Wallet|null $wallet
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereWalletId($value)
 */
class Payment extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'data' => 'array',
        'paid_at' => 'datetime',
    ];

    public static function booted() {
        static::updated(function (Payment $payment) {
            if ($payment->paid_at) {
                if ($payment->type_type === Reservation::class) {
                    $payment->load('type');
                    if ($payment->type && !$payment->type->payment_date) {
                        $all = $payment->data['all'];
                        if ($all) {
                            $payment->type->payment_date = $payment->paid_at;
                            $payment->type->save();
                        } else {
                            $payment_count = $payment->type->payments()->whereNotNull('paid_at')->count();
                            $player_count = 0;
                            foreach ($payment->type->players as $team) {
                                $player_count += $team->number_of_players;
                            }
                            if ($payment_count >= $player_count) {
                                $payment->type->payment_date = $payment->paid_at;
                                $payment->type->save();
                            }
                        }
                    }
                }
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class);
    }

    public function type()
    {
        return $this->morphTo('type')->withTrashed();
    }

    public function wallet() {
        return $this->belongsTo(Wallet::class);
    }

    public function getInvoiceItemName() {
        return match ($this->type_type) {
            Reservation::class => __('Plaćanje rezervacije ') . $this->type_id,
            UserSubscription::class => __('Plaćanje članarine ') . $this->type_id,
            Wallet::class => __('Uplata na račun'),
            default => '',
        };
    }

    public function getInvoiceAmount() {
        return $this->amount;
    }

    public function paymentIntent() {
        return $this->morphOne(PaymentIntent::class, 'paymentable');
    }

    /* public function paidAt() : Attribute {
        return Attribute::make(
            get: function ($value) {
                return $value;
            },
             set: function ($value) {
                $this->attributes['paid_at'] = $value;
                if ($this->attributes['type_type'] === Reservation::class) {
                    $this->load('type');
                    $all = json_decode($this->attributes['data'], true)['all'];
                    if ($all) {
                        $this->type->payment_date = Carbon::now();
                        $this->type->save();
                    } else {
                        $payment_count = $this->type->payments()->whereNotNull('paid_at')->count() + 1; // TODO filter by paid_at
                        $player_count = 0;
                        foreach ($this->type->players as $team) {
                            $player_count += $team->number_of_players;
                        }
                        if ($payment_count === $player_count) {
                            $this->type->payment_date = Carbon::now();
                            $this->type->save();
                        }
                    }
                }
                return strtotime($value);
            }
        );
    }*/

    public function getAmount() {
        return $this->amount * 0.8 / $this->getQuantity();
    }

    public function getTaxes() {
        return [
            'PDV' => [
            'tax' => 25,
            'tax_amount' => $this->amount * 0.2,
            'tax_type' => 'PDV'
        ]];
    }

    public function getTotalAmount() {
        return $this->amount * 0.8;
    }

    public function getBrutoAmount() {
        return $this->amount;
    }

    public function getQuantity() {
        if ($this->attributes['type_type'] === Reservation::class) {
            $r  = Reservation::find($this->attributes['type_id']);
            if ($r) {
                return $r->to->diffInMinutes($this->type->from) / $r->court->reservation_duration;
            }
            // handle deleted reservation, charge back
            return 1;
        }
        return 1;
    }

    public function getNameAttribute() {
        if ($this->attributes['type_type'] === Reservation::class) {
            return __('Plaćanje rezervacije ') . $this->attributes['type_id'];
        }
        return __('Plaćanje članarine');
    }

    public function invoice_item() {
        return $this->morphOne(InvoiceItem::class, 'invoiceable');
    }
}
