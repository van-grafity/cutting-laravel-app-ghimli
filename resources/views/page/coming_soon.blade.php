@extends('layouts.master')
@section('title', 'Coming Soon')
@section('content')
<!-- src="{{ asset('assets/img/coming-soon.png') }}"-->

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="content-title text-center">
                        <img src="{{ asset('assets/img/coming-soon.png') }}" width="50%" height="50%">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('js')
<script type="text/javascript">
</script>
@endpush