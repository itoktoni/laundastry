<!doctype html>
<html lang="en" moznomarginboxes mozdisallowselectionprint>

<head>

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