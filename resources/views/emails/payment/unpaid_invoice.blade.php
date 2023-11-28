<x-mail::message :club="$club" title="Račun">

Poštovani {{ $user->first_name }},

dostavljamo račun {{ $invoice->invoice_number }}.

<x-payments.info :invoice="$invoice"></x-payments.info>


</x-mail::message>
