@component('mail::message')
# Discrepancy Report Notification

**User:** {{ $user->name }} ({{ $user->email }})  
**Date:** {{ $date }}  
**Device:** {{ $device->name ?? 'N/A' }} ({{ $device->category ?? 'N/A' }})  
**Issue Category:** {{ $category }}

## Reported Issue:
{{ $comments }}

@component('mail::button', ['url' => 'mailto:'.$user->email])
Reply to User
@endcomponent

Regards,  
{{ config('app.name') }} Support Team
@endcomponent