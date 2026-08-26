<x-mail::message>
# Hi {{ $user->name }},


Your device {{ $device->name }} has been approved successfully by your Line Manager.
Kindly Log in to your account to view the details and accept the device for it to be marked as active.

<x-mail::button :url="''">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
