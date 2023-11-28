@props(['club', 'title'])
<tr>
<td class="header">
<a href="https://{{ $club->domain }}" style="display: inline-block;">
<img src="{{ $club->logo }}" class="clublogo" alt="Klub Logo">
<div class="title_h3">{{ $club->name }}</div>
@if ($title)
<div class="title_h1">{{ $title }}</div>
@endif
</a>
</td>
</tr>
