@extends('layouts.master')

@section('title', 'QrCode')

@section('content')

<body>

    <div class="container mt-4">

        <div class="card">
            <div class="card-header">
                <h2>{{ $data->color }} - {{ $data->fabric_type }}</h2>
            </div>
            <div class="card-body">
                {!! QrCode::size(300)->generate($data) !!}
            </div>
        </div>
    </div>
</body>

@endsection