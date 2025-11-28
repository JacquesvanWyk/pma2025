<x-mail::message>
# New Contact Form Message

**From:** {{ $data['name'] }}

**Email:** {{ $data['email'] }}

@if($data['phone'])
**Phone:** {{ $data['phone'] }}
@endif

**Message Type:** {{ ucfirst(str_replace('_', ' ', $data['message_type'])) }}

@if($data['prayer_request'] ?? false)
**Include in Prayer Requests:** Yes
@endif

---

## Message

{{ $data['message'] }}

---

<x-mail::button :url="'mailto:' . $data['email']">
Reply to {{ $data['name'] }}
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
