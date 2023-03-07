@extends('layouts.master')

@section('title', 'QrCode')

@section('content')
<div class="row">
            <div class="text-center">
                <!-- <img src="https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl={{$data}}&choe=UTF-8" title="Link to Google.com" /> -->
                <img src="{{ $qrCode }}" alt="">
            </div>
        </div>
    </div>
</div>
@endsection