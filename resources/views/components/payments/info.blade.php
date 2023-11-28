@props(['invoice', 'barcode'])

### Podaci za plaćanje

Iznos: {{ $invoice->payment_details['amount'] }} {{ $invoice->payment_details['currency'] }}

Primatelj: {{ $invoice->payment_details['recipient'] }}

Broj računa primatelja: {{ $invoice->payment_details['iban'] }}

Model: {{ $invoice->payment_details['model'] }}

Poziv na broj primatelja: {{ $invoice->payment_details['reference'] }}

Opis plaćanja: {{ $invoice->payment_details['description'] }}

<img src="data:image/png;base64,{{ $invoice->barcode }}" style="background-color: white;" width="300">

