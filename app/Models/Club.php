<?php

namespace App\Models;

use App\Models\Shop\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Club
 *
 * @property $id
 * @property $name
 * @property $domain
 * @property $subdomain
 * @property $description
 * @property $address
 * @property $postal_code
 * @property $city
 * @property $county
 * @property $region
 * @property $phone
 * @property $fax
 * @property $email
 * @property $has_players
 * @property $has_courts
 * @property $is_active
 * @property string|null $latitude
 * @property string|null $longitude
 * @property string|null $logo
 * @property int|null $weather
 * @property int|null $main_thread
 * @property int|null $validate_user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $admins
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Court[] $courts
 * @property-read mixed $surfaces
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $players
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Club whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Club whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Club whereCounty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Club whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Club whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Club whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Club whereFax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Club whereHasCourts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Club whereHasPlayers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Club whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Club whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Club whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Club whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Club whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Club whereMainThread($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Club whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Club wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Club wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Club whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Club whereSubdomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Club whereValidateUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Club whereWeather($value)
 * @mixin \Eloquent
 * @property-read int|null $admins_count
 * @property-read int|null $courts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Membership[] $memberships
 * @property-read int|null $memberships_count
 * @property-read int|null $players_count
 * @method static \Illuminate\Database\Eloquent\Builder|Club newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Club newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Club query()
 * @property string $type
 * @property bool|null $hide_personal_data
 * @property bool|null $hide_ranks
 * @property array|null $hero_images
 * @property array|null $social
 * @property int|null $payment_accontation
 * @property int|null $payment_online
 * @property-read mixed $all_admins
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Team[] $teams
 * @property-read int|null $teams_count
 * @method static \Illuminate\Database\Eloquent\Builder|Club whereHeroImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Club whereHidePersonalData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Club whereHideRanks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Club wherePaymentAccontation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Club wherePaymentOnline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Club whereSocial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Club whereType($value)
 */
class Club extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'clubs';

    protected $guarded = [];

    protected $casts = [
        'name' => 'string',
        'domain' => 'string',
        'subdomain' => 'string',
        'description' => 'string',
        'address' => 'string',
        'postal_code' => 'string',
        'city' => 'string',
        'county' => 'string',
        'region' => 'string',
        'phone' => 'string',
        'fax' => 'string',
        'email' => 'string',
        'has_players' => 'boolean',
        'has_courts' => 'boolean',
        'is_active' => 'boolean',
        'hide_personal_data' => 'boolean',
        'hide_ranks' => 'boolean',
        'hero_images' => 'array',
        'social' => 'array',
        'show_competitions' => 'boolean',
        'is_w2p' => 'boolean',
        'business_data' => 'array'
    ];

    public function admins()
    {
        return $this->belongsToMany('App\Models\User', 'club_admin', 'club_id', 'user_id')->withPivot('level');
    }

    public function players()
    {
        return $this->belongsToMany(User::class, 'club_user', 'club_id', 'player_id')->using(ClubMembership::class)->withPivot(['status']);
        //return $this->belongsToMany('App\Models\User', 'club_user', 'club_id', 'player_id')->withTimestamps();
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'club_team', 'club_id', 'team_id')->withPivot(['power', 'rating', 'rank']);
        //return $this->belongsToMany('App\Models\User', 'club_user', 'club_id', 'player_id')->withTimestamps();
    }

    public function courts()
    {
        return $this->hasMany('App\Models\Court');
    }

    public function getDomainAttribute($value)
    {
        if ($value) {
            return url($value);
        }

        return $this->attributes['subdomain'].'.'.env('APP_DOMAIN');
    }

    public function getLogoAttribute($value)
    {
        if (trim($value) && \Storage::disk('public')->exists($value)) {
            return url('storage/'.$value);
        }

        return null;
    }

    public function getAdmins()
    {
        return $this->players()->wherePivot('status', 'admin')->get();
    }

    public function getEmailAttribute($value)
    {
        if (strpos($value, '[') > -1) {
            return json_decode($value)[0];
        }

        return $value;
    }

    public function getPhoneAttribute($value)
    {
        if (strpos($value, '[') > -1) {
            return json_decode($value)[0];
        }

        return $value;
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function getSurfacesAttribute()
    {
        $surfaces = [];
        foreach ($this->relations['courts'] as $court) {
            if (! in_array($court->surface, $surfaces)) {
                $surfaces[] = $court->surface;
            }
        }

        return $surfaces;
    }

    public function getAllAdminsAttribute()
    {
        $sups = User::whereIn('id', [1, 2])->get();
        if ($this->admins) {
            $merged = $sups->merge($this->admins);
            return $merged->all();
        }

        return $sups;
    }

    public function getHeroImagesAttribute($value)
    {
        $images = [
            'home' => "https://$this->subdomain.tenis.plus/club-brand-image.jpg",
            'classified' => "https://$this->subdomain.tenis.plus/hero_classifieds.jpg",
            'courts' => "https://$this->subdomain.tenis.plus/hero_courts.jpg",
            'cup' => "https://$this->subdomain.tenis.plus/liga.jpg",
            'doubles' => "https://$this->subdomain.tenis.plus/hero_doubles.jpg",
            'liga' => "https://$this->subdomain.tenis.plus/liga.jpg",
            'live' => "https://$this->subdomain.tenis.plus/hero_news.jpg",
            'news' => "https://$this->subdomain.tenis.plus/hero_news.jpg",
            'notification' => "https://$this->subdomain.tenis.plus/members.jpg",
            'player' => "https://$this->subdomain.tenis.plus/members.jpg",
            'reservation' => "https://$this->subdomain.tenis.plus/members.jpg",
            'result' => "https://$this->subdomain.tenis.plus/members.jpg",
            'school' => "https://$this->subdomain.tenis.plus/hero_school.jpg",
            'trainer' => "https://$this->subdomain.tenis.plus/hero_trainer.jpg",
            'rankings' => "https://$this->subdomain.tenis.plus/hero_ranks.jpg",
            'schedule' => "https://$this->subdomain.tenis.plus/hero_schedule.jpg",
        ];
        if ($value) {
            $value = json_decode($value, true);
            foreach ($images as $key => $image) {
                $images[$key] = isset($value[$key]) && str_contains($value[$key], 'https://') ? $value[$key] : $image;
            }
        }

        return $images;
    }

    public function shopProducts()
    {
        return $this->hasMany(Product::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function tax_class()
    {
        return $this->belongsTo(TaxClass::class);
    }

    public function business_units() {
        return $this->hasMany(BusinessUnit::class);
    }

    public function club_socials() {
        return $this->hasMany(ClubSocial::class);
    }

    public function sports() {
        return $this->belongsToMany(Sport::class);
    }
}
