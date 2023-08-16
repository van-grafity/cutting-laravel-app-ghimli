@extends('layouts.master')

@section('title', 'Status Cutting Order Record')

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

<!-- date_start,  date_end,  gl_number,  status_layer,  status_cut -->
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
                        <label for="gl_number">GL Number</label>
                        <select class="form-control" id="gl_number" name="gl_number">
                            <option value="">-- Select GL Number --</option>
                            @foreach ($gls as $gl)
                                <option value="{{ $gl->id }}">{{ $gl->gl_number }}</option>
                                @foreach ($gl->GLCombine as $glCombine)
                                    <option value="{{ $glCombine->id }}">{{ $glCombine->gl_number }}</option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status_layer">Status Layer</label>
                        <select class="form-control" id="status_layer" name="status_layer">
                            <option value="">-- Select Status Layer --</option>
                            <option value="1">Belum Selesai</option>
                            <option value="2">Selesai Layer</option>
                            <option value="3">Over Layer</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status_cut">Status Cut</label>
                        <select class="form-control" id="status_cut" name="status_cut">
                            <option value="">-- Select Status Cut --</option>
                            <option value="1">Belum Potong</option>
                            <option value="2">Sudah Potong</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-center"> {{-- Center the buttons --}}
                        <a href="javascript:void(0);" class="btn btn-primary mb-2 mr-2" id="btn_print_report">Print Report</a>
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
    $('date_start').datetimepicker({
        format: 'DD-MM-yyyy'
    });
    $('date_end').datetimepicker({
        format: 'DD-MM-yyyy'
    });
</script>

<script type="text/javascript">
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const url = "{{ route('cutting-order.print-status-cutting-order-record') }}"; 
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $('#gl_number').select2();
        $('#status_layer').select2();
        $('#status_cut').select2();

        $('#btn_print_report').click(function(){
            var date_start = $('#date_start').val();
            var date_end = $('#date_end').val();
            var gl_number = $('#gl_number').val();
            var status_layer = $('#status_layer').val();
            var status_cut = $('#status_cut').val();

            window.open(url + '?date_start=' + date_start + '&date_end=' + date_end + '&gl_number=' + gl_number + '&status_layer=' + status_layer + '&status_cut=' + status_cut, '_blank');
        });
    });
</script>
@endpush
    