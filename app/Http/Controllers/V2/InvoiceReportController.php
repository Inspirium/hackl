<?php

namespace App\Http\Controllers\V2;

use App\Actions\Exports\InvoiceExport;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class InvoiceReportController extends Controller
{
    public function index($type = 'json') {
        $invoices = QueryBuilder::for(Invoice::class)
            ->allowedFilters([
                AllowedFilter::exact('club', 'club_id'),
                AllowedFilter::exact('user', 'user_id'),
                AllowedFilter::callback('active_between', function($query, $value) {
                    $query->whereBetween('created_at',  [ Carbon::parse($value[0])->toDateString(),
                        Carbon::parse($value[1]??'now')->addDay()->toDateString() ]);
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
                AllowedFilter::callback('courts', function($query, $value) {
                    /*$query->whereHas('items', function($query) use ($value) {
                        $query
                            ->whereHas('invoiceable', function(Builder $query) use ($value) {
                                $query->whereHas('type', function($query) use ($value) {
                                    $query->whereIn('court_id', $value);
                                });
                            });
                    });*/
                }),
            ])
            ->whereNotNull('invoice_number')
        ->get();

        $items = [
            'cash' => [
                'method' => 'Gotovina',
                'tax_rate' => 25,
                'total_amount' => 0,
                'amount' => 0,
                'tax_amount' => 0,
                'invoices' => 0,
            ],
            'card' => [
                'method' => 'Kartica',
                'tax_rate' => 25,
                'total_amount' => 0,
                'amount' => 0,
                'tax_amount' => 0,
                'invoices' => 0,
            ]
        ];
        foreach ($invoices as $invoice) {
            $should_count = true;
            if (
                \request()->has('filter.courts')
            ) {
                $should_count = false;
                foreach ($invoice->items as $item) {
                    $item->load('invoiceable.type');
                    if (
                        $item->invoiceable_type === Payment::class &&
                        $item->invoiceable->type_type === Reservation::class &&
                        $item->invoiceable->type &&
                        in_array( $item->invoiceable->type->court_id , explode(',', \request()->input('filter.courts')))
                    ) {
                        $should_count = true;
                    }
                }

            }
            if (!$should_count) {
                continue;
            }
            $method = $invoice->payment_method;
            if ($method === 'STRIPE') {
                $method = 'card';
            } else {
                $method = 'cash';
            }
            $items[$method]['total_amount'] += $invoice->total_amount;
            $items[$method]['amount'] += $invoice->amount;
            $items[$method]['tax_amount'] += $invoice->tax_amount;
            $items[$method]['invoices'] += 1;
        }
        if ($type === 'xlsx') {
            $c = new InvoiceExport($items);
            return $c->export();
        }

        return response()->json($items);
    }
}
