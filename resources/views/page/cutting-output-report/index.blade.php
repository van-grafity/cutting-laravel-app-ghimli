@extends('layouts.master')

@section('title', 'Cutting Output Report per GL')

@section('content')
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #ff8000;
        border: 1px solid #ff8000;
        color: #fff;
        padding: 0 10px;
        height: 1.75rem;
        margin-top: .30rem;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: rgba(255,255,255,.7);
        float: right;
        margin-left: 5px;
        margin-right: -2px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #fff;
    }

    .select2-container--default .select2-selection--multiple {
        border-radius: 0;
        border-color: #006fe6;
        min-height: 38px;
    }

    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #006fe6;
        box-shadow: none;
    }
</style>


</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="filter_start_date">Start Date</label>
                        <div class="input-group date" id="filter_start_date" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" data-target="#filter_start_date" name="filter_start_date" id="filter_start_date_input"/>
                            <div class="input-group-append" data-target="#filter_start_date" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="filter_end_date">End Date</label>
                        <div class="input-group date" id="filter_end_date" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" data-target="#filter_end_date" name="filter_end_date" id="filter_end_date_input"/>
                            <div class="input-group-append" data-target="#filter_end_date" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Group</label>
                        <select id="selected_group" name="selected_group[]" class="form-control select2" multiple="multiple" data-placeholder="Select Group" placeholder="Select Group" style="width: 100%;" required>
                            @foreach ($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->group_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-flex justify-content-center">
                        <a href="javascript:void(0)" class="btn btn-primary mb-2 mr-2" id="btn_print_report">Show Report</a>
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
    const show_report_url ='{{ route("cutting-output-report.print") }}';


    $('#filter_start_date, #filter_end_date').datetimepicker({
        format: 'DD/MM/yyyy',
    });
    $('#filter_start_date_input').val(moment().subtract(1, 'months').format('DD/MM/yyyy'));
    $('#filter_end_date_input').val(moment().format('DD/MM/yyyy'));

    $('#selected_group').select2();


</script>

<script type="text/javascript">
$(document).ready(function(){

    // ## Show Flash Message
    let session = {!! json_encode(session()->all()) !!};
    show_flash_message(session);

    $('#btn_print_report').on('click', function() {
        let start_date = moment($('#filter_start_date_input').val(), "DD/MM/YYYY").format('YYYY-MM-DD');
        let end_date = moment($('#filter_end_date_input').val(), "DD/MM/YYYY").format('YYYY-MM-DD');
        let groups = $('#selected_group').val();

        let filter = {
            start_date,
            end_date,
            groups,
        };
        filter_query_string = '?' + new URLSearchParams(filter).toString();

        let url_print_report = show_report_url + filter_query_string;
        window.open(url_print_report);
    })

})
</script>

@endpush