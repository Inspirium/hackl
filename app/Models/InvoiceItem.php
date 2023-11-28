<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'uuid';

    protected $guarded = [];

    protected $appends = [
        'name',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'taxes' => 'array',
    ];

    public function invoice() {
        return $this->hasOne(Invoice::class, 'uuid', 'invoice_id');
    }

    public function invoiceable() {
        return $this->morphTo()->withTrashed();
    }

    protected function name(): Attribute {
        return Attribute::make(
            get: function () {
                if ($this->attributes['name']) {
                    return $this->attributes['name'];
                }
                if ($this->invoiceable) {
                    return $this->invoiceable->name;
                }
                return 'Stavka';
            },
        );
    }
}
