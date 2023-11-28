<?php

namespace App\Http\Controllers\V2;

use App\Actions\Exports\InvoiceExport;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Reservation;
use Carbon\Carbon;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PaymentReportController extends Controller
{
    public function index($type = 'json') {
        $invoices = QueryBuilder::for(Payment::class)
            ->allowedFilters([
                AllowedFilter::exact('club', 'club_id'),
                AllowedFilter::exact('user', 'user_id'),
                AllowedFilter::callback('active_between', function($query, $value) {
                    $query->whereBetween('created_at',  [ Carbon::parse($value[0])->toDateString(), Carbon::parse($value[1]??'now')->addDay()->toDateString() ]);
                }),
                AllowedFilter::callback('payment_method', function($query, $value) {
                    $query->where('data.payment_method', $value);
                }),
                AllowedFilter::exact('type', 'type_type')->default('App\Models\Reservation'),
            ])
            ->whereNotNull('paid_at')
        ->get();

        $items = [
            'cash' => [
                'method' => 'Gotovina',
                'total_amount' => 0,
            ],
            'card' => [
                'method' => 'Kartica',
                'total_amount' => 0,
            ],
            'acc' => [
                'method' => 'Akontacijski',
                'total_amount' => 0,
            ]
        ];
        foreach ($invoices as $invoice) {
            $should_count = true;
            if (
                \request()->has('filter.courts')
            ) {
                $should_count = false;
                    if (
                        $invoice->type_type === Reservation::class &&
                        $invoice->invoiceable->type &&
                        in_array( $invoice->type->court_id, explode(',', \request()->input('filter.courts')))
                    ) {
                        $should_count = true;
                    }

            }
            if (!$should_count) {
                continue;
            }
            $method = $invoice->data['payment_method']??null;
            if ($method)
            $items[$method]['total_amount'] += $invoice->amount;
        }
        if ($type === 'xlsx') {
            $c = new InvoiceExport($items);
            return $c->export();
        }

        return response()->json($items);
    }
}
