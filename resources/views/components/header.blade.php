@props(['customer'])

<h1 style="display:flex;margin-bottom:1rem">
    <img style="max-height: 30px;" class="logo" src="{{ logoUrl() }}" alt="logo">
    <img style="max-height: 30px;" src="{{ imageUrl($customer->customer_logo, 'customer') }}" alt="">
</h1>