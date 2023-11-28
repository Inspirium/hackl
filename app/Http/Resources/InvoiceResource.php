<?php

namespace App\Http\Resources;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Invoice
 */
class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'user' => $this->user,
            'operator' => $this->operator,
            'club' => $this->club,
            'offer_number' => $this->offer_number,
            'offer_data' => $this->offer_data,
            'invoice_number' => $this->invoice_number,
            'invoice_data' => $this->invoice_data,
            'amount' => $this->amount,
            'taxes' => $this->taxes,
            'total_amount' => $this->total_amount,
            'currency' => $this->currency,
            'status' => $this->status,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'payment_reference' => $this->payment_reference,
            'payment_amount' => $this->payment_amount,
            'payment_currency' => $this->payment_currency,
            'payment_data' => $this->payment_data,
            'fiscalization' => $this->fiscalization,
            'payment_date' => $this->payment_date,
            'note' => $this->note,
            'note_internal' => $this->note_internal,
            'items' => $this->items,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'previous_invoices' => $this->when($request->input('include_previous_invoices'), function() {
                $id = $this->items[0]->invoiceable_id;
                $invoices = Invoice::where('user_id', $this->user_id)
                    ->where('club_id', $this->club_id)
                    ->where('uuid', '!=', $this->uuid)
                    ->whereHas('items', function($query) use ($id) {
                        $query->where('invoiceable_type', 'App\Models\UserSubscription');
                           //->where('invoiceable_id', $id);
                    })
                    ->orderBy('created_at', 'desc')
                    ->limit(4)
                    ->get();
                $out = [];
                foreach ($invoices as $invoice) {
                    $out[] = [
                        'id' => $invoice->uuid,
                        'invoice_number' => $invoice->invoice_number,
                        'payment_status' => $invoice->payment_status
                    ];
                }
                return $out;
            }),
        ];
    }
}
