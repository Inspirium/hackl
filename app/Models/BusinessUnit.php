<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessUnit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'business_data',
        'is_active',
        'is_default',
        'invoice_number_structure',
        'club_id',
        'operator_id',
        'has_fiscalization',
        'notes'
    ];

    protected $casts = [
        'business_data' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'has_fiscalization' => 'boolean',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function operator()
    {
        return $this->belongsTo(User::class);
    }
}
