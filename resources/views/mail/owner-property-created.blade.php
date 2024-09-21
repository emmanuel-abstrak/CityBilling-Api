@component('mail::message')
Hi, {{ $user->first_name }}

Your property has been added to **{{ config('app.name') }}** system.

Here are your login details:

Email: {{$user->email}}<br/>
Password: {{ $user->phone_number }}

Thanks,<br>
The {{ config('app.name') }} Team
@endcomponent
