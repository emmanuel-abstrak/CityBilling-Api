@component('mail::message')
Hi, {{ $user->first_name }}

Welcome to **{{ config('app.name') }}**.

Please make use of the code below to complete your registration:

**{{ $user->password_code }}**

Thanks,<br>
The {{ config('app.name') }} Team
@endcomponent
