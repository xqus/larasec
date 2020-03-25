@component('mail::message')
# laraSec report

@forelse ($alerts as $alert)
### {{ $alert['title'] }}
{{$alert['package']}} {{$alert['version']}}
@empty

@endforelse

@forelse ($updates as $alert)
### {{$alert['package']}} {{$alert['version']}}
{{ $alert['description'] }}
@empty

@endforelse

Thanks,<br>
{{ config('app.name') }}
@endcomponent
