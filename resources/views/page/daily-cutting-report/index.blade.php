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
    <div class="row justify-content-center"> {{-- Center the card within the row --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-center mb-4">Daily Cutting Report</h6> {{-- Center the title --}}
                    <div class="form-group">
                        <label for="filter_date">Filter Date</label>
                        <div class="input-group date" id="filter_date" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" data-target="#filter_date" name="filter_date" id="filter_date_input"/>
                            <div class="input-group-append" data-target="#filter_date" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
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
    $('#filter_date').datetimepicker({
        format: 'DD/MM/yyyy',
    });

    $('#filter_date_input').val(moment().format('DD/MM/yyyy'));
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