@extends('layouts.master')

@section('title', 'Daily Cutting Report')

@section('content')
<div class="row justify-content-center pt-5">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label for="filter_date">Filter Date</label>
                    <div class="input-group date" id="filter_date" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#filter_date" name="filter_date" id="filter_date_input" required/>
                        <div class="input-group-append" data-target="#filter_date" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 text-right">
                        @can('daily-cutting-report.print-yds')
                        <a href="javascript:void(0);" class="btn btn-info" id="btn_print_report_yds">Print Report ( YDs Version )</a>
                        @endcan
                        <a href="javascript:void(0);" class="btn btn-primary" id="btn_print_report">Print Report</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')

<script type="text/javascript">
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const print_report_url ='{{ route("daily-cutting-report.print") }}';
    const print_report_yds_url ='{{ route("daily-cutting-report.print-yds") }}';
</script>

<script type="text/javascript">
$(document).ready(function(){

    // ## Show Flash Message
    let session = {!! json_encode(session()->all()) !!};
    show_flash_message(session);

    $('#filter_date').datetimepicker({
        format: 'DD/MM/yyyy',
    });

    $('#filter_date_input').val(moment().format('DD/MM/yyyy'));

    $('#btn_print_report').on('click', function() {
        if($('#filter_date_input').val().length > 1) {
            filter_date = moment($('#filter_date_input').val(), "DD/MM/YYYY").format('YYYY-MM-DD');
            let url_print_report = print_report_url + '?date=' + filter_date;
            window.open(url_print_report);
        } else {
            swal_failed({
                title: "Silahkan masukkan tanggal yang benar"
            })
        }
    });
    $('#btn_print_report_yds').on('click', function() {
        if($('#filter_date_input').val().length > 1) {
            filter_date = moment($('#filter_date_input').val(), "DD/MM/YYYY").format('YYYY-MM-DD');
            let url_print_report_yds = print_report_yds_url + '?date=' + filter_date;
            window.open(url_print_report_yds);
        } else {
            swal_failed({
                title: "Silahkan masukkan tanggal yang benar"
            })
        }
    });
})
</script>

@endpush