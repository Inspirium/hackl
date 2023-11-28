<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'completed_at' => 'datetime',
        'data' => 'array',
    ];

    public function orderable() {
        return $this->morphTo();
    }

    public function assignee() {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function assigner() {
        return $this->belongsTo(User::class, 'assigner_id');
    }


    public function club() {
        return $this->belongsTo(Club::class);
    }
    public function scopeCreatedBetween(Builder $builder, $value1, $value2 = null) {
        if (!$value2) {
            $value2 = 'now';
        }
        return $builder->whereBetween('created_at',  [ Carbon::parse($value1), Carbon::parse($value2) ]);
    }
}
