<?php

namespace App\Actions;

use App\Models\BusinessUnit;
use App\Models\Country;
use App\Models\Invoice;
use App\Models\InvoiceItem;

class CreateInvoice {
    public function handle($customer, $type, $items, $object = null) {
        $club = $type->club_id;
        $b = BusinessUnit::where('club_id', $club)->where('is_default', true)->first();
        $operator = $b->operator->id??1;
        $country_id =  $type->data['company']['country_id'] ?? $customer->country_id;
        $country = Country::find($country_id);
        /** @var Invoice $invoice */
        $invoice = Invoice::create([
            'user_id' => $customer->id,
            'operator_id' => $operator,
            'club_id' => $club,
            'invoice_data' => [
                'name' => $customer->display_name,
                'company' => $type->data['company']['name'] ?? null,
                'address' => $type->data['company']['address'] ?? $customer->address,
                'address2' => $type->data['company']['address2'] ?? null,
                'city' => $type->data['company']['city'] ?? $customer->city,
                'postal_code' => $type->data['company']['postal_code'] ?? $customer->postal_code,
                'country' => $country->name ?? null,
                'vat_id' => $type->data['company']['vat_id'] ?? null,
            ],
            'amount' => 0,
            'taxes' => 0,
            'total_amount' => 0,
            'currency' => 'EUR',
            'payment_status' => 'PAID',
            'status' => 'PAID',
            'payment_date' => now(),
            'payment_reference' => $object ? $object->id : null,
            'payment_method' => $object ? 'STRIPE': 'CASH',
            'company_id' => $type->data['company']['id'] ?? null,
        ]);
        $taxes = [];
        $neto = 0;
        $bruto = 0;
        foreach($items as $item) {
            $t = $item->getTaxes();
            $i = InvoiceItem::create([
                'invoice_id' => $invoice->uuid,
                'invoiceable_id' => $item->id,
                'invoiceable_type' => $item::class,
                'amount' => $item->getAmount(),
                'quantity' => $item->getQuantity(),
                'total_amount' => $item->getTotalAmount(),
                'taxes' => $t,
                'currency' => 'EUR',
                'name' => $item->name,
            ]);
            foreach ($t as $type => $tax) {
                if (isset($taxes[$type])) {
                    $taxes[$type]['tax_amount'] += $tax['tax_amount'];
                } else {
                    $taxes[$type] = $tax;
                }
                $neto += $item->getTotalAmount();
                $bruto += $item->getBrutoAmount();
            }
        }
        $invoice->amount = $neto;
        $invoice->total_amount = $bruto;
        $invoice->taxes = $taxes;
        if ($b) {
            $invoice->business_unit()->associate($b->id);
        }
        $invoice->save();
        $invoice->createInvoiceNumber();
        return $invoice;
    }
}
