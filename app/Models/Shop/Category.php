<?php

namespace App\Models\Shop;

use App\Models\Club;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'shop_categories';

    protected $guarded = [];

    public function products() {
        return $this->hasMany(Product::class);
    }

    public function parent() {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children() {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function club() {
        return $this->belongsTo(Club::class);
    }

}
