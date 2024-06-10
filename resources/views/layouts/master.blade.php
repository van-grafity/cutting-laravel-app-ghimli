@extends('adminlte::page')

@section('title')
    @yield('title')
    @component('components.breadcrumb')@endcomponent
@stop

@section('css')
    <!-- CSS -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet" />
    
@stop

@section('content')
    @yield('content')
@stop

@push('js')
<script>
    $('.select2.no-search-box').select2({
        minimumResultsForSearch: Infinity
    });

    // ## bagian ini bertujuan agar saat select2 di open, langsung mengarah ke search box focusnya. bisa langsung ketik di keyboard searching nya. tidak perlu diklik menggunakan mouse dulu
    $('.select2').on('select2:open', function (e) {
        document.querySelector('.select2-search__field').focus();
    });
</script>
@endpush

@yield('last-body')
@include('layouts.scripts-vendor')
@include('layouts.scripts-app')