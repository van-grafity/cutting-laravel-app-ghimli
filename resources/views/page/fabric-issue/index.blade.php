@extends('layouts.master')

@section('title', 'Fabric Issue Request')

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
                        <label for="fbr_number">Fabric Request Number</label>
                        <select class="form-control" id="fbr_number" name="fbr_number">
                            <option value="">-- Select Fabric Request Number --</option>
                            @foreach ($fabric_requisitions as $fabric_requisition)
                                <option value="{{ $fabric_requisition->id }}">{{ $fabric_requisition->serial_number }}</option>
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

</script>

<script type="text/javascript">
    $(document).ready(function(){
        $('#fbr_number').select2();

        $('#date_start').val(moment().subtract(2, 'week').format('yyyy-MM-DD'));
        $('#date_end').val(moment().format('yyyy-MM-DD'));

        
        $('#date_start').change(function(){
            var date_start = $('#date_start').val();
            var date_end = $('#date_end').val();
            if(date_start != '' && date_end != ''){
                $.ajax({
                    url: "{{ route('fabric-requisition-serial-number') }}",
                    type: "GET",
                    data: {
                        date_start: date_start,
                        date_end: date_end
                    },
                    success: function(data){
                        var html = '';
                        html += '<option value="">-- Select Fabric Request Number --</option>';
                        $.each(data.data, function(key, value){
                            html += '<option value="'+value.id+'">'+value.serial_number+'</option>';
                        });
                        $('#fbr_number').html(html);
                    }
                });
            }
        });

        $('#date_end').change(function(){
            var date_start = $('#date_start').val();
            var date_end = $('#date_end').val();
            console.log(date_start + ' ' + date_end);
            if(date_start != '' && date_end != ''){
                $.ajax({
                    url: "{{ route('fabric-requisition-serial-number') }}",
                    type: "GET",
                    data: {
                        date_start: date_start,
                        date_end: date_end
                    },
                    success: function(data){
                        var html = '';
                        html += '<option value="">-- Select Fabric Request Number --</option>';
                        $.each(data.data, function(key, value){
                            html += '<option value="'+value.id+'">'+value.serial_number+'</option>';
                        });
                        $('#fbr_number').html(html);
                    }
                });
            }
        });


        $('#btn_detail').click(function(){
            var fbr_number = $('#fbr_number').val();
            if(fbr_number != ''){
                window.location.href = "{{ url('fabric-issue') }}/"+fbr_number;
            }else{
                alert('Please select Fabric Request Number');
            }
        });
        
        $('#date_start').change();
    });
            
</script>
@endpush