<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-notify@1.0.4/dist/simple-notify.min.css">
<script src="https://cdn.jsdelivr.net/npm/simple-notify@1.0.4/dist/simple-notify.min.js"></script>

@if ($errors->any())
<script>
    @foreach($errors->all() as $error)
        @php
            $string = Str::of($error);
            $required = ' wajib diisi';
            if($string->contains($required)){
                $error = formatAttribute($string->before($required)).$required ?? $error;
            }
        @endphp
        new Notify({
            status: 'error',
            title: 'Error',
            text: '{{ $error }}',
            effect: 'fade',
            speed: 300,
            customClass: null,
            customIcon: null,
            showIcon: true,
            showCloseButton: true,
            autoclose: true,
            autotimeout: 3000,
            gap: 20,
            distance: 20,
            type: 1,
            position: 'right top'
        });
    @endforeach
</script>
@endif

@if(session()->has('success') && !request()->ajax())
<script>
    new Notify({
        status: 'success',
        title: 'Success',
        text: '{{ session('success') }}',
        effect: 'fade',
        speed: 300,
        customClass: null,
        customIcon: null,
        showIcon: true,
        showCloseButton: true,
        autoclose: true,
        autotimeout: 3000,
        gap: 20,
        distance: 20,
        type: 1,
        position: 'right top'
    });
</script>
@php
session()->forget('success');
@endphp
@endif

@if(session()->has('error') && !request()->ajax())
<script>
    new Notify({
        status: 'error',
        title: 'Error',
        text: '{{ session('error') }}',
        effect: 'fade',
        speed: 300,
        customClass: null,
        customIcon: null,
        showIcon: true,
        showCloseButton: true,
        autoclose: true,
        autotimeout: 3000,
        gap: 20,
        distance: 20,
        type: 1,
        position: 'right top'
    });
</script>
@php
session()->forget('error');
@endphp
@endif