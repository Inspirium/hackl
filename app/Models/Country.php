<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'code',
        'locale',
        'currency',
        'flag',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'name' => 'array',
    ];
    public function cities() {
        return $this->hasMany(City::class);
    }

    public function name(): Attribute {
        return Attribute::make(
            get: function ($value) {
                $value = json_decode($value, true);
                return $value[app()->getLocale()]??$value['en'];
            },
        );
    }
}
