@extends('layouts.master')

@section('title', 'Cutting Order Completion')

@section('content')
<style>
    .header-wrapper {
        display: grid;
        grid-template-columns: 1fr 1fr;
    }
    .date-filter {
        justify-self: start;
    }

    .action-button-group {
        justify-self: end;
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="gl_number">GL Number</label>
                        <select class="form-control" id="gl_number" name="gl_number">
                            <option value="">-- Select GL Number --</option>
                            @foreach ($gls as $gl)
                                <option value="{{ $gl->id }}">{{ $gl->gl_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="d-flex justify-content-center">
                        <a href="javascript:void(0);" class="btn btn-primary mb-2 mr-2" id="btn_print_report">Print</a>
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

<script type="text/javascript">
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const url = "{{ route('cutting-order.cutting-completion-report') }}";
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $('#gl_number').select2();
        
        $('#btn_print_report').click(function(){
            var gl_number = $('#gl_number').val();
            
            window.open(url + '?gl_number=' + gl_number, '_blank');
        });
    });
</script>
@endpush
    