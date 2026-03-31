@php
$action = request()->get('action');
$height = '100px';
if($action == 'excel'){
    $height = '15%';
}
@endphp

@if (auth()->user()->role != 'user')
    <img style="position: absolute;left:40%;top:5px;" src="{{ env('APP_LOGO') ? url('storage/'.env('APP_LOGO')) : url('assets/media/image/logo.png') }}" alt="logo" height="{{ $height }}">
@endif