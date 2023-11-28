<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Surface
 *
 * @property int $id
 * @property string $designation
 * @property string $title
 * @property string $badge
 * @property string $fill
 * @property string $reserved
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Court[] $courts
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Surface whereBadge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Surface whereDesignation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Surface whereFill($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Surface whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Surface whereReserved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Surface whereTitle($value)
 * @mixin \Eloquent
 * @property-read int|null $courts_count
 * @method static \Illuminate\Database\Eloquent\Builder|Surface newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Surface newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Surface query()
 */
class Surface extends Model
{
    protected $table = 'surfaces';

    public $timestamps = false;

    protected $guarded = [];

    public function courts()
    {
        return $this->hasMany('App\Models\Court');
    }
}
