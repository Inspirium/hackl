<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Models\BusinessUnit;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\SchoolGroup;
use App\Models\Subscription;
use App\Models\TaxClass;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ObjectInvoices extends Controller
{
    public function __construct(Request $request) {
        $this->middleware('auth:api');
        if ($request->route()) {
            $name = explode('.', $request->route()->getName())[0];
            switch ($name) {
                case 'school_group':
                    $this->route = 'school_group';
                    $this->model = SchoolGroup::class;
                    break;

            }
        }
    }

    public function store(Request $request, $object_id) {
        $object = $this->model::findOrFail($object_id);
        $invoices = [];
        /** @var UserSubscription $user_subscription */
        foreach($object->subscriptions as $user_subscription) {
            // create Invoice - use subscription
            /** @var Subscription $subscription */
            $subscription = $user_subscription->subscription;
            $company = null; //TODO:
            $b = BusinessUnit::where('club_id', $subscription->club_id)->where('is_default', true)->firstOrFail();
            $invoice = Invoice::create([
                'user_id' => $user_subscription->user_id,
                'operator_id' => \Auth::id(),
                'club_id' => $subscription->club_id,
                'invoice_data' => [
                    'name' => $user_subscription->user->display_name,
                    'company' => $company?->name ?? null,
                    'address' => $company?->address ?? $user_subscription->user->address,
                    'address2' => $company?->address2 ?? null,
                    'city' => $company?->city ?? $user_subscription->user->city,
                    'postal_code' => $company?->postal_code ?? $user_subscription->user->postal_code,
                    'country' => $company?->country ?? $user_subscription->user->country,
                    'vat_id' => $company?->vat_id ?? null,
                ],
                'payment_method' => 'TRANSACTION',
                'payment_date' => '',
                'payment_status' => $request->input('payment_status') ?? 'UNPAID',
                'payment_currency' => $subscription->currency,
                'payment_amount' => $subscription->price,
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
                            'tax_amount' => $subscription->price * (1 - 100 / (100 + $tax->rate)),
                            'tax_type' => $tax->name,
                        ];
                    }
                }
                $invoiceItem = InvoiceItem::create([
                    'name' => $request->input('name') ? $subscription->name . ' - ' . $request->input('name') : $subscription->name,
                    'invoice_id' => $invoice->uuid,
                    'amount' => $subscription->price,
                    'invoiceable_id' => $subscription->id,
                    'invoiceable_type' => Subscription::class,
                    'quantity' => 1,
                    'taxes' => $t,
                    'total_amount' => $subscription->price,
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
            $invoice->amount = $subscription->price - $total_tax;
            $invoice->total_amount = $subscription->price;;
            $invoice->taxes = $taxes;
            $invoice->save();
            //$invoice->createInvoiceNumber();
            //$invoice->sendEmail();
            $invoices[] = $invoice;
        }
        return JsonResource::make($invoices);
    }
}
