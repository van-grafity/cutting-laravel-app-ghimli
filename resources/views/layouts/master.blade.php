@extends('adminlte::page')

@section('title')
    @yield('title')
    @component('components.breadcrumb')@endcomponent
@stop

@section('content_header')
    <!-- CSS -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet" />
    
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">@yield('title')</h1>
            </div>
        </div>
    </div>
@stop

@section('content')
    @yield('content')
@stop

@yield('last-body')
@include('layouts.scripts-vendor')
@include('layouts.scripts-app')