<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourtWeatherUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'court_id',
        'note',
        'from',
        'to',
        'created_by',
    ];

    public function court()
    {
        return $this->belongsTo(Court::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
