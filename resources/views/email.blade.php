<x-mail::message>
<h1> Hello {{$name}}</h1>

Please use this link for verify your email

<x-mail::button :url="$link">
Click Hear
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
