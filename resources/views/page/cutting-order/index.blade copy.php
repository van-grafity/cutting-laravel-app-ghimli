@extends('layouts.master')

@section('title', 'Cutting Order Record')

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
<div class="container-fluid">
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
                            <a href="javascript:void(0);" class="btn btn-success mb-2" id="btn_filter_search" style="height: fit-content">Search</a>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mb-1">
                        <a href="javascript:void(0);" class="btn btn-success mb-2 d-none" id="btn_modal_create">Create</a>
                    </div>

                    <table class="table table-bordered table-hover" id="cutting_order_table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="">No. </th>
                                <th scope="col" class="">Serial Number</th>
                                <th scope="col" class="">No Laying Sheet</th>
                                <th scope="col" class="">Color</th>
                                <th scope="col" class="">Table No</th>
                                <th scope="col" class="">Status</th>
                                <th scope="col" class="">Action</th>
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

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const delete_url ='{{ route("cutting-order.destroy",":id") }}';
    
    async function delete_cuttingOrder(cutting_order_id) {

        data = { title: "Are you sure?" };
        let confirm_delete = await swal_delete_confirm(data);
        if(!confirm_delete) { return false; };

        let url_delete = delete_url.replace(':id',cutting_order_id);
        let data_params = { token };

        result = await delete_using_fetch(url_delete, data_params)
        if(result.status == "success"){
            swal_info({
                title : result.message,
                reload_option: true, 
            });
        } else {
            swal_failed({ title: result.message });
        }
    };

</script>

<script type="text/javascript">
    $(function (e) {
        $('#cutting_order_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('/cutting-order-data') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'serial_number', name: 'serial_number'},
                {data: 'no_laying_sheet', name: 'no_laying_sheet'},
                {data: 'color', name: 'color'},
                {data: 'table_number', name: 'table_number'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            lengthChange: true,
            searching: true,
            autoWidth: false,
            responsive: true,
        });
    });
</script>

<script type="text/javascript">
    $('#filter_date').datetimepicker({
        format: 'DD/MM/yyyy',
    });

    $('#filter_date_input').val(moment().format('DD/MM/yyyy'));
    
    $(document).ready(function() {
        $('#filter_date').on('change.datetimepicker', function() {
            // console.log($('#filter_date_input').val());
        });

    });
</script>
@endpush