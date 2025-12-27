<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

  <title>Print</title>

<link rel="preconnect" href="https://rsms.me/">
<link rel="stylesheet" href="https://rsms.me/inter/inter.css">


@if(isset($print))
<link rel="stylesheet" href="{{ asset('assets/css/print.css') }}" type="text/css">
@else
<link rel="stylesheet" href="{{ asset('assets/css/report.css') }}" type="text/css">
@endif

</head>

<body>
    @yield('header')
    @yield('content')
</body>

</html>