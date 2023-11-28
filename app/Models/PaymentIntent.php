<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentIntent extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function paymentable()
    {
        return $this->morphTo()->withTrashed();
    }
}
