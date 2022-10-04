@component('mail::message')
# {{ $details['title'] }}

Your payment is confirmed


@component('mail::button', ['url' => $details['url']])
Login
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
