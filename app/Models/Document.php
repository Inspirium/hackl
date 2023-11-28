<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Document
 *
 * @property int $id
 * @property string|null $title
 * @property int|null $user_id
 * @property string|null $path
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Document newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Document newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Document query()
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereUserId($value)
 * @mixin \Eloquent
 */
class Document extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $guarded = [];

    public function getPathAttribute($value)
    {
        return url('storage/'.$value);
    }
}
