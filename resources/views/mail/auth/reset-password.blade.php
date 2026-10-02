<x-mail::message>
# Set Up Your Account Password

Hello {{ $user->name ?? 'User' }},

We have recieved a request to reset your password.

Please click the button below to create your new password:

@component('mail::button', ['url' => $url])
Set Password
@endcomponent

For security reasons, this link will expire after some time. If the link expires before you complete the process, you may request a new one.

If you did not expect this email, please disregard it. No further action is required.

Thank you,
{{ config('app.name') }}

</x-mail::message>