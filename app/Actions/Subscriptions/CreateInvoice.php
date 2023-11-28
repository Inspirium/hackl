<?php

namespace App\Actions\Subscriptions;

use App\Models\BusinessUnit;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Subscription;
use App\Models\UserSubscription;

class CreateInvoice {
    public function execute($object) {
        $request = request();
        foreach($object->subscriptions as $user_subscription) {
            if ($user_subscription->is_paused) {
                continue;
            }
            // create Invoice - use subscription
            /** @var Subscription $subscription */
            $subscription = $user_subscription->subscription;
            $company = null; //TODO:
            if ($subscription->business_unit) {
                $b = $subscription->business_unit;
            } else {
                $b = BusinessUnit::where('club_id', $subscription->club_id)->where('is_default', true)->firstOrFail();
            }
            $price = $user_subscription->price;
            if (!$price) {
                $price = $subscription->price;
            }
            $invoice = Invoice::create([
                'user_id' => $user_subscription->team->primary_contact_id,
                'operator_id' => \Auth::id() ?? 2,
                'club_id' => $subscription->club_id,
                'invoice_data' => [
                    'name' => $user_subscription->team->display_name,
                    'company' => $company?->name ?? null,
                    'address' => $company?->address ?? $user_subscription->team->primary_contact->address,
                    'address2' => $company?->address2 ?? null,
                    'city' => $company?->city ?? $user_subscription->team->primary_contact->city,
                    'postal_code' => $company?->postal_code ?? $user_subscription->team->primary_contact->postal_code,
                    'country' => $company?->country ?? $user_subscription->team->primary_contact->country,
                    'vat_id' => $company?->vat_id ?? null,
                ],
                'payment_method' => 'TRANSACTION',
                'payment_date' => '',
                'payment_status' => $request->input('payment_status') ?? 'UNPAID',
                'payment_currency' => $subscription->currency,
                'payment_amount' => $price,
                'note' => $request->input('note'),
                //'internal_note' => $request->input('internal_note'),
                'company_id' => $company?->id ?? null,
                'business_unit_id' => $b->id,
            ]);
            $taxes = [];
            $neto = 0;
            $bruto = 0;
            $t = [];
            if (isset($item['tax']) && $item['tax']) {
                $tax = $subscription->tax_class;
                if ($tax) {
                    $t[$tax->name] = [
                        'tax' => $tax->rate,
                        'tax_amount' => $price * (1 - 100 / (100 + $tax->rate)),
                        'tax_type' => $tax->name,
                    ];
                }
            }
            $invoiceItem = InvoiceItem::create([
                'name' => $request->input('name') ? $subscription->name . ' - ' . $request->input('name') : $subscription->name,
                'invoice_id' => $invoice->uuid,
                'amount' => $price,
                'invoiceable_id' => $user_subscription->id,
                'invoiceable_type' => UserSubscription::class,
                'quantity' => 1,
                'taxes' => $t,
                'total_amount' => $price,
                'currency' => $subscription->currency,
            ]);
            $total_tax = 0;
            foreach ($t as $type => $tax) {
                if (isset($taxes[$type])) {
                    $taxes[$type]['tax_amount'] += $tax['tax_amount'];
                } else {
                    $taxes[$type] = $tax;
                }
                $total_tax += $tax['tax_amount'];
            }
            $invoice->amount = $price - $total_tax;
            $invoice->total_amount = $price;;
            $invoice->taxes = $taxes;
            $invoice->save();
            //$invoice->createInvoiceNumber();
            //$invoice->sendEmail();
            $invoices[] = $invoice;
        }
        return $invoices;
    }
}
