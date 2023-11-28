<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\OtherExpense
 *
 * @property int $id
 * @property string $name
 * @property float $price
 * @property int|null $club_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|OtherExpense newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OtherExpense newQuery()
 * @method static \Illuminate\Database\Query\Builder|OtherExpense onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|OtherExpense query()
 * @method static \Illuminate\Database\Eloquent\Builder|OtherExpense whereClubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OtherExpense whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OtherExpense whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OtherExpense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OtherExpense whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OtherExpense wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OtherExpense whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|OtherExpense withTrashed()
 * @method static \Illuminate\Database\Query\Builder|OtherExpense withoutTrashed()
 * @mixin \Eloquent
 */
class OtherExpense extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
}
