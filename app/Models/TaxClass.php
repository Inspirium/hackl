<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxClass extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'rate',
        'active_from',
        'active_to',
        'country_id',
    ];

    protected $casts = [
        'active_from' => 'datetime',
        'active_to' => 'datetime',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
