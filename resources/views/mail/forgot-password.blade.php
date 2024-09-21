@component('mail::message')
Hi, {{ $user->first_name }}

You requested a password reset for your **{{ config('app.name') }}** account.

Please make use of the code below:

**{{ $user->password_code }}**

Thanks,<br>
The {{ config('app.name') }} Team
@endcomponent
