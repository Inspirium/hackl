<?php

namespace App\Models;

use App\Mail\SendUnpaidInvoice;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory, HasUuids;
    protected $guarded = [];

    protected $primaryKey = 'uuid';

    protected $with = ['business_unit'];


    protected $casts = [
        'offer_data' => 'array',
        'invoice_data' => 'array',
        'payment_data' => 'array',
        'fiscalization' => 'array',
        'payment_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'taxes' => 'array',
    ];

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function club() {
        return $this->hasOne(Club::class, 'id', 'club_id');
    }

    public function operator() {
        return $this->hasOne(User::class, 'id', 'operator_id');
    }

    public function items() {
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'uuid');
    }

    protected function fiscalization(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if ($value) {
                    return json_decode($value, true);
                }
                // TODO create fiscal object
                return [
                    'jir' => '',
                    'zki' => '',
                    'url' => '',
                    'fiscalized_at' => '',
                ];
            },
            set: function ($value) {
                return json_encode($value);
            }
        );
    }

    protected function invoiceNumber(): Attribute
    {
        $this->load('business_unit');
        return Attribute::make(
            get: function ($value) {
                if ($value) {
                    return $value . '-' . $this->business_unit->business_data['unit'] . '-' . $this->business_unit->business_data['device'];
                }
                return null;
            },
        );
    }

    public function business_unit() {
        return $this->belongsTo(BusinessUnit::class, 'business_unit_id', 'id');
    }

    public function getPaymentMethod() {
        if ($this->attributes['payment_method'] === 'STRIPE') {
            return __('Kartica');
        }
        return __('Gotovina');
    }

    public function getTaxAmountAttribute() {
        $tax = 0;
        foreach ($this->taxes as $item) {
            $tax += $item['tax_amount'];
        }
        return $tax;
    }

    public function getBarcodeAttribute() {
        $price = sprintf('%015d', $this->total_amount*100);
        $first = $this->user->display_name;
        $second = $this->user->address;
        $third = $this->user->postal_code . ' ' . $this->user->city;
        if ($this->invoice_data['company']) {
            $company = Company::find($this->invoice_data['company']);
            $first = $company->name;
            $second = $company->address;
            $third = $company->postal_code . ' ' . $company->city;
        }

        $sender = $this->club->name;
        $sender .= "\n" . $this->club->address;
        $sender .= "\n" . $this->club->postal_code . ' ' . $this->club->city;
        $sender .= "\n" . $this->club->business_data['bank_account'];
        $text = <<<HUB
HRVHUB30
EUR
{$price}
{$first}
{$second}
{$third}
{$sender}
HR01
{$this->invoice_number}
COST
Račun {$this->invoice_number}
HUB;
        return \Milon\Barcode\Facades\DNS2DFacade::getBarcodePNG($text, 'PDF417,,4');
    }

    public function createInvoiceNumber() {
        $old = Invoice::query()->where('business_unit_id', $this->business_unit_id)->orderBy('invoice_number', 'desc')->first();
        $this->invoice_number = $old ? (int)$old->invoice_number + 1 : 1;
        $this->save();
    }

    public function sendEmail() {
        $emails = ['marko@inspirium.hr'];
        if ($this->user->email) {
            $emails[] = $this->user->email;
        }
        if ($this->invoice_data['company']) {
            $company = Company::find($this->invoice_data['company']);
            if ($company->email) {
                $emails[] = $company->email;
            }
        }
        if ($this->user->parents) {
            foreach ($this->user->parents as $parent) {
                $emails[] = $parent->email;
            }
        }
        \Mail::to($emails)->send(new SendUnpaidInvoice($this->user, $this));
    }

    public function getReferenceAttribute() {
        return filter_var($this->invoice_number, FILTER_SANITIZE_NUMBER_INT);
    }

    public function getPaymentDetailsAttribute() {
        return [
            'recipient' => $this->club->name,
            'address' => $this->club->address,
            'postal_code' => $this->club->postal_code,
            'city' => $this->club->city,
            'iban' => $this->club->business_data['bank_account'],
            'model' => '01',
            'reference' => $this->reference,
            'amount' => $this->total_amount,
            'currency' => $this->currency,
            'description' => 'Račun broj ' . $this->invoice_number,
        ];
    }

}
