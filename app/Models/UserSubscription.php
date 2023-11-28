<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserSubscription extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function subscription() {
        return $this->belongsTo(Subscription::class);
    }

    public function subscribable() {
        return $this->morphTo();
    }

    public function team() {
        return $this->belongsTo(Team::class);
    }

    public function invoices() {
        return $this->morphMany(InvoiceItem::class, 'invoiceable');
    }

    protected function name(): Attribute {
        return Attribute::make(
            get: function () {
                return $this->subscription->name;
            },
        );
    }
}
