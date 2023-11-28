<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WorkingHours
 *
 * @property int $id
 * @property int $court_id
 * @property string $cron
 * @property int|null $working
 * @property \Carbon\Carbon|null $active_from
 * @property \Carbon\Carbon|null $active_to
 * @property float $price
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkingHours whereActiveFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkingHours whereActiveTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkingHours whereCourtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkingHours whereCron($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkingHours whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkingHours wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkingHours whereWorking($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingHours newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingHours newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingHours query()
 * @property int|null $membership_id
 * @property-read \App\Models\Court $court
 * @property-read \App\Models\Membership|null $membership
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingHours whereMembershipId($value)
 */
class WorkingHours extends Model
{
    protected $table = 'working_hours';

    public $timestamps = false;

    protected $casts = [
        'active_from' => 'datetime',
        'active_to' => 'datetime',
    ];

    public function court()
    {
        return $this->belongsTo(Court::class, 'court_id');
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    public function taxClass()
    {
        return $this->belongsTo(TaxClass::class);
    }
}
