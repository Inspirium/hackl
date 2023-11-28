<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Trainer
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $description
 * @property int $show_phone
 * @property float|null $price
 * @property int $court_included
 * @property int $available
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer newQuery()
 * @method static \Illuminate\Database\Query\Builder|Trainer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer whereAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer whereCourtIncluded($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer whereShowPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trainer whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Trainer withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Trainer withoutTrashed()
 * @mixin \Eloquent
 */
class Trainer extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
