<x-mail::message>
# Welcome to Motora!

Hello {{ $user->name }},

An account has been created for you at **{{ $currentUser->organization->name }}** on Motora.

To get started, please click the button below to set your password and access your dashboard.

<x-mail::button :url="$link">
Set My Password
</x-mail::button>

This link will expire in 3 days for your security.

If you did not expect this email, feel free to ignore it — no action is needed.

Thanks,<br>
The {{ config('app.name') }} Team

<hr />
If you're having trouble clicking the "Set My Password" button, copy and paste the URL below into your web browser: <a href="{{ $link }}">{{ $link }}</a>
</x-mail::message>
