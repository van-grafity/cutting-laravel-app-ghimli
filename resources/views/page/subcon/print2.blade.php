@extends('layouts.master')

@section('title', 'Cutting Report by Group')

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
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="date_start">Date Start</label>
                            <input type="date" class="form-control" id="date_start" name="date_start">
                        </div>
                        <div class="col-sm-6">
                            <label for="date_end">Date End</label>
                            <input type="date" class="form-control" id="date_end" name="date_end">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <label for="group_id">Group</label>
                            <select class="form-control" id="group_id" name="group_id">
                                <option value="">-- Select Group --</option>
                                @foreach ($group as $group)
                                    <option value="{{ $group->id }}">{{ $group->group_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <br/>
                    <div class="d-flex justify-content-center">
                        <a href="javascript:void(0);" class="btn btn-primary mb-2 mr-2" id="btn_print_report">Print Summary</a>
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
    const url ='{{ route("subcon-cutting.summary-report-group-cutting-order-record") }}';
    $(document).ready(function(){
        $('#group_id').select2({
            placeholder: '-- Select Group --'
        });

        $('#btn_print_report').click(function() {
            let date_start = $('#date_start').val();
            let date_end = $('#date_end').val();
            let group_id = $('#group_id').val();

            if (date_start == '' || date_end == '' || group_id == '') {
                alert('Please fill all the fields');
                return false;
            }

            window.open(url + '?date_start=' + date_start + '&date_end=' + date_end + '&group_id=' + group_id, '_blank');
        });
    });
</script>
@endpush