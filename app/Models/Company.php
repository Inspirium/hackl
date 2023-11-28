<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'vat_id',
        'address',
        'address2',
        'city',
        'postal_code',
        'phone',
        'email',
        'country_id',
    ];

    public function country() {
        return $this->belongsTo(Country::class);
    }

    public function users() {
        return $this->belongsToMany(User::class);
    }
}
