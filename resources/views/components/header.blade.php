@props(['customer'])

<h1 style="display:flex">
    <img style="max-height: 30px;" class="logo" src="{{ logoUrl() }}" alt="logo">
    <img src="{{ imageUrl($customer->customer_logo, 'customer') }}" alt="">
</h1>