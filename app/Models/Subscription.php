<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    public $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'warnings' => 'array',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function users() {
        return $this->belongsToMany(Team::class, 'user_subscriptions');
    }

    public function tax_class() {
        return $this->belongsTo(TaxClass::class);
    }

    public function business_unit() {
        return $this->belongsTo(BusinessUnit::class);
    }
}
