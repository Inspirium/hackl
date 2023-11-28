<?php

namespace App\Actions\Membership;

use App\Models\BusinessUnit;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Membership;
use App\Models\Subscription;
use App\Models\UserMembership;
use App\Models\UserSubscription;

class CreateInvoice {
    public function execute(Membership $membership) {
        $request = request();
        $invoices = [];
        $b = BusinessUnit::where('club_id', $membership->club_id)->where('is_default', true)->firstOrFail();
        if (!$b) {
           return [];
        }
        $membership->load(['user_memberships']);
        if ($request->input('user.id')) {
            $memberships = $membership->user_memberships()->where('user_id', $request->input('user.id'))->get();
        } else {
            $memberships = $membership->user_memberships;
        }
        foreach($memberships as $user_membership) {
            if (!$user_membership->user) {
                continue;
            }
            $price = $user_membership->price;
            if (!$price) {
                $price = $membership->subscription['price'];
            }
            if (!$price) {
                continue;
            }
            $invoice = Invoice::create([
                'user_id' => $user_membership->user_id,
                'operator_id' => \Auth::id() ?? 2, // TODO: business unit operator
                'club_id' => $membership->club_id,
                'invoice_data' => [
                    'name' => $user_membership->user->display_name,
                    'company' => $company?->name ?? null,
                    'address' => $company?->address ?? $user_membership->user->address,
                    'address2' => $company?->address2 ?? null,
                    'city' => $company?->city ?? $user_membership->user->city,
                    'postal_code' => $company?->postal_code ?? $user_membership->user->postal_code,
                    'country' => $company?->country ?? $user_membership->user->country,
                    'vat_id' => $company?->vat_id ?? null,
                ],
                'payment_method' => 'TRANSACTION',
                'payment_date' => '',
                'payment_status' => $request->input('payment_status') ?? 'UNPAID',
                'payment_currency' => $membership->currency ?? 'EUR',
                'payment_amount' => $price,
                'note' => $request->input('note'),
                //'internal_note' => $request->input('internal_note'),
                'company_id' => $company?->id ?? null,
                'business_unit_id' => $b->id,
            ]);
            $taxes = [];
            $t = [];
            if (isset($item['tax']) && $item['tax']) {
                $tax = $membership->tax_class;
                if (!$tax) {
                    $tax = $membership->club->tax_classes()->where('is_default', true)->first();
                }
                if ($tax) {
                    $t[$tax->name] = [
                        'tax' => $tax->rate,
                        'tax_amount' => $price * (1 - 100 / (100 + $tax->rate)),
                        'tax_type' => $tax->name,
                    ];
                }
            }
            $invoiceItem = InvoiceItem::create([
                'name' => $request->input('name') ? $membership->name . ' - ' . $request->input('name') : $membership->name,
                'invoice_id' => $invoice->uuid,
                'amount' => $price,
                'invoiceable_id' => $user_membership->id,
                'invoiceable_type' =>UserMembership::class,
                'quantity' => 1,
                'taxes' => $t,
                'total_amount' => $price,
                'currency' => $membership->currency ?? 'EUR',
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
