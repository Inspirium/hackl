<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserSocial
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $social_id
 * @property string|null $social_email
 * @property string|null $token
 * @property string $service
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserSocial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSocial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSocial query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSocial whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSocial whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSocial whereService($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSocial whereSocialEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSocial whereSocialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSocial whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSocial whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSocial whereUserId($value)
 * @mixin \Eloquent
 */
class UserSocial extends Model
{
    protected $table = 'user_socials';

    protected $fillable = [
        'user_id',
        'social_id',
        'social_email',
        'service',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
