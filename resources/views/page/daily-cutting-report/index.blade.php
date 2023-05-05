@extends('layouts.master')

@section('title', 'Daily Cutting Report')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-1">
                        <div class="form-group">
                            <!-- <label for="filter_date" class="form-label">Filter Date</label> -->
                            <div class="input-group date" id="filter_date" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#filter_date" name="filter_date" id="filter_date_input"/>
                                <div class="input-group-append" data-target="#filter_date" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                            
                        </div>
                        <a href="javascript:void(0);" class="btn btn-success mb-2" id="btn_filter_search" style="height: fit-content">Search</a>
                    </div>
                    <table class="table table-bordered table-hover" id="daily_cutting_table">
                        <thead class="">
                            <tr>
                                <th scope="col" style="width: 70px;">#</th>
                                <th scope="col" class="text-left">Buyer</th>
                                <th scope="col" class="text-left">Style</th>
                                <th scope="col" class="text-left">GL#</th>
                                <th scope="col" class="text-left">Color</th>
                                <th scope="col" class="text-left">MI Qty</th>
                                <th scope="col" class="text-left">Total Qty Per Day</th>
                            </tr>
                        </thead>
                        <!-- <tbody>
                        </tbody> -->
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('js')
<script type="text/javascript">

$(document).ready(function(){

    // ## Show Flash Message
    let session = {!! json_encode(session()->all()) !!};
    show_flash_message(session);

    $('#btn_filter_search').click((e) => {
        console.log($('#filter_date_input').val());
    })

    $('#filter_date').on('change.datetimepicker', function() {
        // console.log($('#filter_date_input').val());
    });

})
</script>

<script type="text/javascript">
$(function (e) {
    
    // ## Datepicker Initialize
    $('#filter_date').datetimepicker({
        format: 'DD/MM/yyyy',
    });

    $('#filter_date_input').val(moment().format('DD/MM/yyyy'));

    // ## Datatable Initialize
    $('#daily_cutting_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('/daily-cutting-data') }}",
            type: 'GET',
            data: {
                'date': "2023-04-17"
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'buyer', name: 'buyer'},
            {data: 'style', name: 'style'},
            {data: 'gl_number', name: 'gl_number'},
            {data: 'color', name: 'color'},
            {data: 'mi_qty', name: 'mi_qty'},
            {data: 'total_qty_per_day', name: 'total_qty_per_day'},
        ],
        lengthChange: true,
        searching: true,
        autoWidth: false,
        responsive: true,
    });


});
</script>

@endpush