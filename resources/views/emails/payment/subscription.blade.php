<x-mail::message :club="$club" title="Račun">

Poštovani {{ $user->first_name }},

dostavljamo dostavljamo račun za mjesec svibanj.

<x-payments.info :invoice="$invoice" :barcode="$barcode"></x-payments.info>


</x-mail::message>
