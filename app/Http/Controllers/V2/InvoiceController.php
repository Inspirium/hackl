<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;
use App\Models\BusinessUnit;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\TaxClass;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class InvoiceController extends Controller
{

    public function __construct() {
        $this->middleware('auth:api')->except(['pdf']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = QueryBuilder::for(Invoice::class)
            ->allowedFilters([
                AllowedFilter::exact('status', 'payment_status'),
                AllowedFilter::exact('club', 'club_id')->default(request()->get('club')->id),
                AllowedFilter::exact('user', 'user_id'),
                AllowedFilter::callback('display_name', function($query, $value) {
                    $query->whereHas('user', function($q) use ($value) {
                        $q->where('first_name', 'like', '%'.$value.'%')
                        ->orWhere('last_name', 'like', '%'.$value.'%');
                    });
                }),
                AllowedFilter::callback('active_between', function($query, $value) {
                    $query->whereBetween('created_at',  [ Carbon::parse($value[0]), Carbon::parse($value[1]??'now') ]);
                }),
                AllowedFilter::callback('payment_method', function($query, $value) {
                    if ($value === 'card') {
                        $value = 'STRIPE';
                    }
                    if ($value === 'cash') {
                        $value = 'CASH';
                    }
                    $query->where('payment_method', $value);
                }),
                AllowedFilter::callback('pending', function($query, $value) {
                    if ($value) {
                        $query->whereNull('invoice_number');
                    } else {
                        $query->whereNotNull('invoice_number');
                    }
                })->default(false),
                AllowedFilter::callback('item_type', function($query, $value) {
                    $query->whereHas('items', function($q) use ($value) {
                        $q->where('invoiceable_type', $value);
                    });
                }),
            ])
            ->allowedSorts([
                'created_at',
                'updated_at',
                'total_amount',
                'amount',
            ])->defaultSort('-created_at')
            ->paginate(request()->input('limit'))
            ->appends(request()->query());

        $is = QueryBuilder::for(Invoice::class)
            ->allowedFilters([
                AllowedFilter::exact('status', 'payment_status'),
                AllowedFilter::exact('club', 'club_id')->default(request()->get('club')->id),
                AllowedFilter::exact('user', 'user_id'),
                AllowedFilter::callback('display_name', function($query, $value) {
                    $query->whereHas('user', function($q) use ($value) {
                        $q->where('first_name', 'like', '%'.$value.'%')
                            ->orWhere('last_name', 'like', '%'.$value.'%');
                    });
                }),
                AllowedFilter::callback('active_between', function($query, $value) {
                    $query->whereBetween('created_at',  [ Carbon::parse($value[0]), Carbon::parse($value[1]??'now') ]);
                }),
                AllowedFilter::callback('payment_method', function($query, $value) {
                    if ($value === 'card') {
                        $value = 'STRIPE';
                    }
                    if ($value === 'cash') {
                        $value = 'CASH';
                    }
                    $query->where('payment_method', $value);
                }),
                AllowedFilter::callback('pending', function($query, $value) {
                    if ($value) {
                        $query->whereNull('invoice_number');
                    } else {
                        $query->whereNotNull('invoice_number');
                    }
                })->default(false),
                AllowedFilter::callback('item_type', function($query, $value) {
                    $query->whereHas('items', function($q) use ($value) {
                        $q->where('invoiceable_type', $value);
                    });
                }),
            ])
            ->sum('total_amount');

        return InvoiceResource::collection($invoices)
            ->additional([
            'meta' => [
                'total_amount' => $is,
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // get items for invoice
        $items = $request->input('items');
        $user = $request->input('user');
        if (isset($user['id']) && $user['id']) {
            $user = User::find($user['id']);
            $user = $user->toArray();
        }
        $company = $request->input('company');
        if (isset($company['id']) && $company['id']) {
            $company = Company::find($company);
        }
        $bunit = $request->input('business_unit.id');
        if ($bunit) {
            $b = BusinessUnit::find($bunit);
        } else {
            $b = BusinessUnit::where('club_id', $request->get('club')->id)->where('is_default', true)->first();
        }
        $invoice = Invoice::create([
            'user_id' => $user['id'],
            'operator_id' => \Auth::id(),
            'club_id' => $request->get('club')->id,
            'invoice_data' => [
                'name' => $user['display_name'],
                'company' => $company?->name ?? null,
                'address' => $company?->address ?? $user['address'],
                'address2' => $company?->address2 ?? null,
                'city' => $company?->city ?? $user['city'],
                'postal_code' => $company?->postal_code ?? $user['postal_code'],
                'country' => $company?->country ?? $user['country'],
                'vat_id' => $company?->vat_id ?? null,
            ],
            'payment_method' => $request->input('payment_method'),
            'payment_date' => $request->input('payment_status') ? now() : null,
            'payment_status' => $request->input('payment_status') ?? 'PAID',
            'payment_currency' => $request->input('payment_currency'),
            'payment_amount' => $request->input('payment_amount'),
            'note' => $request->input('note'),
            //'internal_note' => $request->input('internal_note'),
            'company_id' => $company?->id ?? null,
            'business_unit_id' => $b->id,
        ]);
        $taxes = [];
        $neto = 0;
        $bruto = 0;
        $total_tax = 0;
        foreach ($items as $item) {
            $item['invoice_id'] = $invoice->id;
            $object = false;
            if (isset($item['type']) && $item['type']) {
                $object = $item['type']::find($item['id']);
            }

            $t = [];
            if (isset($item['tax']) && $item['tax']) {
                $tax = TaxClass::find($item['tax']['id']);
                $t[$tax->name] = [
                        'tax' => $tax->rate,
                        'tax_amount' => $item['amount'] * $item['quantity'] * (1- 100/(100+$tax->rate)),
                        'tax_type' => $tax->name,
                ];
            }
            $invoiceItem = InvoiceItem::create([
                'name' => $object ? $object->name : $item['name'],
                'invoice_id' => $invoice->uuid,
                'amount' => $object ? $object->price : $item['amount'],
                'invoiceable_id' => $object ? $object->id : 0,
                'invoiceable_type' => $object ? $object->getClass() : '',
                'quantity' => $item['quantity'],
                'taxes' => $t,
                'total_amount' => $object ? $object->price * $item['quantity'] : $item['amount'] * $item['quantity'],
                'currency' => 'EUR',
            ]);
            foreach ($t as $type => $tax) {
                if (isset($taxes[$type])) {
                    $taxes[$type]['tax_amount'] += $tax['tax_amount'];
                } else {
                    $taxes[$type] = $tax;
                }
                $total_tax += $tax['tax_amount'];
            }
            $bruto += $object ? $object->price * $item['quantity'] : $item['amount'] * $item['quantity'];
        }
        $invoice->amount = $bruto - $total_tax;
        $invoice->total_amount = $bruto;
        $invoice->taxes = $taxes;
        $invoice->save();
        $invoice->createInvoiceNumber();
        $invoice->sendEmail();
        return InvoiceResource::make($invoice);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $t = Invoice::with(['club', 'user', 'operator', 'items'])->find($id);
        return InvoiceResource::make($t);
        return \PDF::loadView('invoices.invoice', ['invoice' => $t])->stream($id . '.pdf');
    }

    public function pdf(string $id)
    {
        $t = Invoice::with(['club', 'user', 'operator', 'items'])->find($id);
        return \PDF::loadView('invoices.invoice', ['invoice' => $t])->stream($id . '.pdf');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $payment_status = $request->input('payment_status');
        if($payment_status === 'PAID' && $invoice->payment_status !== 'PAID') {
            $invoice->payment_status = $payment_status;
            $invoice->payment_date = now();
            $invoice->save();
        }
        if ($request->input('items')) {
            $total = 0;
            foreach ($request->input('items') as $item) {
                $invoiceItem = InvoiceItem::find($item['uuid']);
                $price = $item['amount'];
                if ($price != $invoiceItem->amount) {
                    $invoiceItem->amount = $price;
                    $invoiceItem->total_amount = $price;
                    // TODO: taxes
                    $invoiceItem->save();
                }
                $total += $price;
            }
            if ($invoice->total_amount != $total) {
                $invoice->amount = $total;
                $invoice->total_amount = $total;
                $invoice->payment_amount = $total;
                // TODO: taxes
                $invoice->save();
            }
        }
        return InvoiceResource::make($invoice);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        if ($invoice->invoice_number) {
            // TODO:
            return response()->json(['message' => 'Cannot delete invoice with invoice number'], 400);
        }
        $invoice->delete();
        return response()->json(['message' => 'Invoice deleted']);
    }

    public function approve(Request $request)
    {
        $invoices = $request->input('invoices');
        foreach ($invoices as $invoice_id) {
            $invoice = Invoice::find($invoice_id);
            $invoice->createInvoiceNumber();
            $invoice->sendEmail();
        }
        return response()->json(['message' => 'Invoices approved']);
    }

    public function send(Request $request) {
        $invoices = $request->input('invoices');
        if (!$invoices) {
            $invoices = QueryBuilder::for(Invoice::class)
                ->whereNull('invoice_number')
                ->allowedFilters([
                    'status',
                    AllowedFilter::callback('item_type', function($query, $value) {
                        $query->whereHas('items', function($q) use ($value) {
                            $q->where('invoiceable_type', $value);
                        });
                    }),
                    AllowedFilter::exact('club', 'club_id')->default(request()->get('club')->id),
                    AllowedFilter::exact('user', 'user_id'),
                    AllowedFilter::callback('display_name', function($query, $value) {
                        $query->whereHas('user', function($q) use ($value) {
                            $q->where('first_name', 'like', '%'.$value.'%')
                                ->orWhere('last_name', 'like', '%'.$value.'%');
                        });
                    }),
                    AllowedFilter::callback('active_between', function($query, $value) {
                        $query->whereBetween('created_at',  [ Carbon::parse($value[0]), Carbon::parse($value[1]??'now') ]);
                    }),
                    AllowedFilter::callback('payment_method', function($query, $value) {
                        if ($value === 'card') {
                            $value = 'STRIPE';
                        }
                        if ($value === 'cash') {
                            $value = 'CASH';
                        }
                        $query->where('payment_method', $value);
                    }),
                ])
                ->whereNull('invoice_number')
                ->get();
        }
        foreach($invoices as $invoice) {
            $i = Invoice::findOrFail($invoice['id']);
            $i->createInvoiceNumber();
            $i->sendEmail();
        }
        return JsonResource::make($invoices);
    }

    public function deleteMany(Request $request) {
        $club = $request->get('club');
        if (!\Auth::user()->is_admin->contains($club->id)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $invoices = QueryBuilder::for(Invoice::class)
            ->where('club_id', $club->id)
            ->whereNull('invoice_number')
            ->allowedFilters([
                'status',
                AllowedFilter::callback('item_type', function($query, $value) {
                    $query->whereHas('items', function($q) use ($value) {
                        $q->where('invoiceable_type', $value);
                    });
                }),
            ])
            ->delete();
        return response()->json(['message' => 'Invoices deleted']);
    }
}
