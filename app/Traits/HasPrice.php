<?php

namespace App\Traits;

trait HasPrice
{

    public $price;
    public $tax_rate = 0.05;

    public function getAmountAttribute()
    {
        return $this->price;
    }

    public function getTaxAttribute()
    {
        return $this->amount * $this->tax_rate;
    }

    public function getTotalAmountAttribute()
    {
        return $this->amount + $this->tax;
    }
}
