@extends('layouts.master')

@section('title', 'Daily Cutting Report')

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
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="mb-1 header-wrapper">
                        <div class="date-filter">
                            <div class="form-group">
                                <!-- <label for="filter_date" class="form-label">Filter Date</label> -->
                                <div class="input-group date" id="filter_date" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#filter_date" name="filter_date" id="filter_date_input"/>
                                    <div class="input-group-append" data-target="#filter_date" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="action-button-group">
                            <a href="javascript:void(0);" class="btn btn-primary mb-2" id="btn_print_report" style="height: fit-content">Print Report</a>
                            <!-- <a href="" class="btn btn-primary mb-2" id="btn_print_report" style="height: fit-content" target="_blank">Print Report</a> -->
                            <a href="javascript:void(0);" class="btn btn-success mb-2" id="btn_filter_search" style="height: fit-content">Search</a>
                        </div>
                    </div>
                    <table class="table table-bordered table-hover" id="daily_cutting_table">
                        <thead class="">
                            <tr>
                                <th scope="col" style="width: 20px;">#</th>
                                <th scope="col" class="text-left">Buyer</th>
                                <th scope="col" class="text-left">Style</th>
                                <th scope="col" class="text-left" style="width: 50px;">GL#</th>
                                <th scope="col" class="text-left">Color</th>
                                <th scope="col" class="text-left">MI Qty</th>
                                <th scope="col" class="text-left">Previous Balance</th>
                                <th scope="col" class="text-left">Total Qty Per Day</th>
                                <th scope="col" class="text-left">Accumulation (pcs)</th>
                                <th scope="col" class="text-left">Completed (%)</th>
                                <th scope="col" class="text-left">Action</th>
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


<!-- Modal Section -->
<div class="modal fade" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="modal_formLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_formLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="detail-section my-5 px-5">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered text-center" id="table_daily_cutting_detail">
                                <thead>
                                    <tr>
                                        <th>Operator</th>
                                        <th>Total (pcs)</th>
                                    </tr>
                                </thead>
                                <tbody class="align-top">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-md-12 text-right">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" style="width:100px;">OK</button>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@push('js')


<script type="text/javascript">
// $(function (e) {
    
    // ## Datepicker Initialize
    $('#filter_date').datetimepicker({
        format: 'DD/MM/yyyy',
    });

    $('#filter_date_input').val(moment().format('DD/MM/yyyy'));

    // ## Datatable Initialize
    let table_cutting = $('#daily_cutting_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('/daily-cutting-data') }}",
            type: 'GET',
            data: {
                // 'date': "2023-04-17",
                'date': function() { return moment($('#filter_date_input').val(), "DD/MM/YYYY").format('YYYY-MM-DD') },
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'buyer', name: 'buyer'},
            {data: 'style', name: 'style'},
            {data: 'gl_number', name: 'gl_number'},
            {data: 'color', name: 'color'},
            {data: 'mi_qty', name: 'mi_qty'},
            {data: 'previous_balance', name: 'previous_balance'},
            {data: 'total_qty_per_day', name: 'total_qty_per_day'},
            {data: 'accumulation', name: 'accumulation'},
            {data: 'completed', name: 'completed'},
            {data: 'action', name: 'action'},
        ],
        lengthChange: true,
        searching: true,
        autoWidth: false,
        responsive: true,
    });


// });
</script>

<script type="text/javascript">
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const url_daily_detail ='{{ route("daily-cutting.detail") }}';
    const print_report_url ='{{ route("daily-cutting.print-report") }}';

    
    async function show_detail(id, fitler_date) {
        data_params = {
            id: id,
            filter_date: fitler_date,
        }
        result = await using_fetch(url_daily_detail, data_params, "GET");
        if(result.status !== "success") {
            return false;
        }

        // console.log(data_params);
        // console.log(result);

        let body_element = '';
        let total_element = '';
        let sum_total_pcs = 0;
        $('#table_daily_cutting_detail tbody').html(body_element)
        if(result.data.length <= 0) {
            body_element = `
                <tr>
                    <td colspan="2" class="text-center">Tidak ada Data</td>
                </tr>
            `;
            $('#table_daily_cutting_detail tbody').html(body_element);
        }

        result.data.forEach( data_operator => {
            sum_total_pcs = sum_total_pcs + data_operator.total_qty;
            body_element = `
                <tr>
                    <td>${data_operator.operator}</td>
                    <td>${data_operator.total_qty}</td>
                </tr>
            `;
            $('#table_daily_cutting_detail tbody').append(body_element)
        });
        if(result.data.length > 0) {
            total_element = `
                <tr>
                    <th>Total</th>
                    <th>${sum_total_pcs}</th>
                </tr>
            `;
            $('#table_daily_cutting_detail tbody').append(total_element)
        }


        $('#modal_formLabel').text("Detail")
        $('#modal_form').modal('show')
    }
</script>

<script type="text/javascript">

$(document).ready(function(){

    // ## Show Flash Message
    let session = {!! json_encode(session()->all()) !!};
    show_flash_message(session);

    $('#btn_filter_search').click((e) => {
        table_cutting.ajax.reload();

    })

    $('#filter_date').on('change.datetimepicker', function() {
        // console.log($('#filter_date_input').val());
    });

    $('#btn_print_report').on('click', function() {
        filter_date = moment($('#filter_date_input').val(), "DD/MM/YYYY").format('YYYY-MM-DD');
        let url_print_report = print_report_url + '?date=' + filter_date;
        console.log(url_print_report);
        // location.href = url_print_report;
        window.open(url_print_report);
    })

})
</script>

@endpush