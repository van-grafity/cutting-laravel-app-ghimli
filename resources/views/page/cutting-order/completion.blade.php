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
                        <label for="start_cut">Start Cut</label>
                        <input type="date" class="form-control" id="start_cut" name="start_cut" placeholder="Date Start">
                    </div>
                    <div class="form-group">
                        <label for="finish_cut">Finish Cut</label>
                        <input type="date" class="form-control" id="finish_cut" name="finish_cut" placeholder="Date End">
                    </div>
                    <div class="form-group">
                        <label for="gl_number">GL Number</label>
                        <select class="form-control" id="gl_number" name="gl_number">
                            <option value="">-- Select GL Number --</option>
                            @foreach ($gls as $gl)
                                <option value="{{ $gl->id }}">{{ $gl->gl_number }}</option>
                            @endforeach
                        </select>
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
    $('start_cut').datetimepicker({
        format: 'DD-MM-yyyy'
    });
    $('finish_cut').datetimepicker({
        format: 'DD-MM-yyyy'
    });
</script>

<script type="text/javascript">
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const url = "{{ route('cutting-order.cutting-completion-report') }}";
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $('#gl_number').select2();
        
        $('#btn_print_report').click(function(){
            var start_cut = $('#start_cut').val();
            var finish_cut = $('#finish_cut').val();
            var gl_number = $('#gl_number').val();
            
            window.open(url + '?start_cut=' + start_cut + '&finish_cut=' + finish_cut + '&gl_number=' + gl_number, '_blank');
        });
    });
</script>
@endpush
    