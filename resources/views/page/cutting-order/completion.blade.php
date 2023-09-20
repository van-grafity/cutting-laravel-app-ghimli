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
                        <label for="date_start">Date Start</label>
                        <input type="date" class="form-control" id="date_start" name="date_start" placeholder="Date Start">
                    </div>
                    <div class="form-group">
                        <label for="date_end">Date End</label>
                        <input type="date" class="form-control" id="date_end" name="date_end" placeholder="Date End">
                    </div>
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
    $('date_start').datetimepicker({
        format: 'DD-MM-yyyy'
    });
    $('date_end').datetimepicker({
        format: 'DD-MM-yyyy'
    });
</script>

<script type="text/javascript">
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const url = "{{ route('cutting-order.cutting-completion-report') }}";
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $('#btn_print_report').click(function(){
            var date_start = $('#date_start').val();
            var date_end = $('#date_end').val();
            
            window.open(url + '?date_start=' + date_start + '&date_end=' + date_end, '_blank');
        });
    });
</script>
@endpush
    