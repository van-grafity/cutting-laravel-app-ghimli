@extends('layouts.master')

@section('title', 'Summary Cutting by Group Report')

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
                            <label for="gl_number">Group</label>
                            <select class="form-control" id="gl_number" name="gl_number">
                                <option value="">-- Select Group --</option>
                                @foreach ($group as $group)
                                    <option value="{{ $group->id }}">{{ substr($group->group_name, 0) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label for="lp_serial_number">Laying Planning Serial Number</label>
                        <select class="form-control" id="lp_serial_number" name="lp_serial_number">
                            <option value="">-- Select Laying Planning Serial Number --</option>
                            @foreach ($laying_plannings as $laying_planning)
                                <option value="{{ $laying_planning->id }}">{{ $laying_planning->serial_number }}</option>
                            @endforeach
                        </select>
                    </div>
                     -->
                    <!-- <div class="card bg-danger text-white">
                        <div class="card-body">
                            <p class="card-text">*Planning serial number muncul hanya berdasarkan data subcon</p>
                        </div>
                    </div> -->

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
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const print_report_url ='{{ route("subcon-cutting.cutting-report-subcon", ":id") }}';
    $(document).ready(function(){
    $('#lp_serial_number').select2();

    $('#btn_print_report').click(function() {
        let lp_serial_number = $('#lp_serial_number').val();
        if (lp_serial_number == '') {
            swal_failed('Please select Laying Planning Serial Number');
            return false;
        }
        let url = print_report_url.replace(':id', lp_serial_number);
        window.open(url, '_blank');
    });
});
</script>
@endpush