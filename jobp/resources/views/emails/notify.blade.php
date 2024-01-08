<x-mail::message>
    # Introduction

    The body of your message.

    <p>Plan: {{$plan}} </p>
    <p>Your plan ends on: {{$billingEnds}} </p>
    <x-mail::button :url="''">
        Button Text
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
