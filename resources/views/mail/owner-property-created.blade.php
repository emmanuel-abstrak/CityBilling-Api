@component('mail::message')
Hi, {{ $user->first_name }}

Welcome to **{{ config('app.name') }}**.

To activate your account please use the verification code below:

Thanks,<br>
The {{ config('app.name') }} Team
@endcomponent
