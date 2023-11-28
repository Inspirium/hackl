<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $invoice = Invoice::create([
            'user_id' => '2',
            'club_id' => '4',
            'operator_id' => '1',
            'offer_number' => '1',
            'offer_data' => [
                'offer_date' => '2023-06-01',
                'offer_validity' => '2023-06-31',
                'business_id' => 'POSL1',
                'business_unit' => '1',
            ],
            'amount' => '80',
            'taxes' => [
                [
                    'tax' => '25',
                    'tax_amount' => '20',
                    'tax_type' => 'PDV',
                ]
            ],
            'total_amount' => '100',
            'currency' => 'EUR',
            'status' => 'DRAFT',
        ]);
        InvoiceItem::create([
            'invoice_id' => $invoice->uuid,
            'invoiceable_id' => 156,
            'invoiceable_type' => 'App\Models\UserSubscription',
            'quantity' => 1,
            'amount' => 80,
            'taxes' => [
                [
                    'tax' => '25',
                    'tax_amount' => '20',
                    'tax_type' => 'PDV',
                ]
            ],
            'total_amount' => 100,
            'currency' => 'EUR',
        ]);
        $invoice = Invoice::create([
            'user_id' => '2',
            'club_id' => '4',
            'operator_id' => '1',
            'invoice_number' => '1',
            'invoice_data' => [
                'invoice_date' => '2023-06-01',
                'invoice_validity' => '2023-06-31',
                'business_id' => 'POSL1',
                'business_unit' => '1',
            ],
            'payment_method' => 'STRIPE',
            'payment_status' => 'PAID',
            'payment_reference' => 'ch_1J2jD2L5bGaKb6YXQ1Q2jD2L',
            'payment_date' => Carbon::now(),
            'payment_currency' => 'EUR',
            'payment_amount' => '100',
            'payment_data' => [
                'disbursement_date' => '2023-06-01',
                'disbursement_amount' => '97',
                'disbursement_currency' => 'EUR',
            ],
            'amount' => '80',
            'taxes' => [
                [
                    'tax' => '25',
                    'tax_amount' => '20',
                    'tax_type' => 'PDV',
                ]
            ],
            'total_amount' => '100',
            'currency' => 'EUR',
            'status' => 'PAID',
            'fiscalization' => [
                'jir' => '1234567890123456',
                'zki' => '1234567890123456',
                'url' => 'https://cistest.apis-it.hr:8449/FiskalizacijaServiceTest',
                'fiscalized_at' => Carbon::now(),
            ],
        ]);
        InvoiceItem::create([
            'invoice_id' => $invoice->uuid,
            'invoiceable_id' => 156,
            'invoiceable_type' => 'App\Models\UserSubscription',
            'quantity' => 1,
            'amount' => 80,
            'taxes' => [
                [
                    'tax' => '25',
                    'tax_amount' => '20',
                    'tax_type' => 'PDV',
                ]
            ],
            'total_amount' => 100,
            'currency' => 'EUR',
        ]);
    }
}
