@extends('layouts.master')

@section('title', 'Cut Piece Stock')

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
    <div class="row justify-content-center"> {{-- Center the card within the row --}}
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
                    <div class="form-group">
                        <label for="gl_number">GL</label>
                        <select class="form-control" id="gl_number" name="gl_number">
                            <option value="">-- Select GL Number --</option>
                            @foreach ($gls as $gl)
                                <option value="{{ $gl->id }}">{{ $gl->gl_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex justify-content-center"> {{-- Center the buttons --}}
                        <a href="javascript:void(0);" class="btn btn-primary mb-2 mr-2" id="btn_detail">Detail</a>
                        {{-- Add other buttons here if needed --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
        $('date_start').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
        $('date_end').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });

</script>

<script type="text/javascript">

</script>

<script type="text/javascript">
    $(document).ready(function(){
        $('date_start').datetimepicker({
            format: 'DD-MM-yyyy',
            autoclose: true,
            todayHighlight: true
        });
        $('date_end').datetimepicker({
            format: 'DD-MM-yyyy',
            autoclose: true,
            todayHighlight: true
        });
        $('#gl_number').select2({
            placeholder: '-- Select GL Number --'
        });

        $('#btn_detail').click(function(){
            var gl_number = $('#gl_number').val();
            if(gl_number != ''){
                window.location.href = "{{ url('cut-piece-stock-report') }}";
            }else{
                alert('Please select GL Number');
            }
        });
    });
            
</script>
@endpush