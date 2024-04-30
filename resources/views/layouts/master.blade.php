@extends('adminlte::page')

@section('title')
    @yield('title')
    @component('components.breadcrumb')@endcomponent
@stop

@section('content_header')
    <!-- CSS -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet" />
    
@stop

@section('content')
    @yield('content')
@stop

@yield('last-body')
@include('layouts.scripts-vendor')
@include('layouts.scripts-app')