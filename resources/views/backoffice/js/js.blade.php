{{-- <script src="{{asset('https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js')}}"></script>
<script src="{{asset('https://maps.googleapis.com/maps/api/js?key=["your-google-map-api"]&libraries=places')}}"></script> --}}
<script src="{{asset('dist/js/app.js')}}"></script>
<script src="{{asset('dist/js/ckeditor-classic.js')}}"></script>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    jspie();
</script>

@php
$sh="";
@endphp

@if($errors->count())
@foreach ($errors->all() as $error)
    @php
        $sh.=" ".$error."<br>";
    @endphp
@endforeach
@php
echo "
<script>
    Swal.fire({
icon: 'error',
title: 'Oops...',
html: '$sh',

})
</script>
"
@endphp
@endif

@if (session('error'))
<script>
    Swal.fire({
icon: 'error',
title: 'Oops...',
html: '{{ session('error') }}',

})</script>
@endif
