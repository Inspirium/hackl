<x-mail::layout :club="$club" :title="$title">
{{-- Header --}}
<x-slot:header>
<x-mail::header :club="$club" :title="$title">
{{ config('app.name') }}
</x-mail::header>
</x-slot:header>
{{-- Above --}}
@isset($above)
<x-slot:above class="inner-body">
{{ $above }}
</x-slot:above>
@endisset
{{-- Body --}}
{{ $slot }}
@isset($above)
<x-slot:below>
{{ $below }}
</x-slot:below>
@endisset
{{-- Footer --}}
<x-slot:footer>
<x-mail::footer>
© {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
</x-mail::footer>
</x-slot:footer>
</x-mail::layout>
