<x-mail::message :club="$club" title="Kontakt poruka">
Ime: {{ $request->input('name') }}<br>
Email: {{ $request->input('email') }}<br>
Phone: {{ $request->input('mob') }}<br>
Message: {{ $request->input('message') }}
</x-mail::message>
