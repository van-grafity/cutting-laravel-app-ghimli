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
                    <div class="content-title text-center">
                        <h3>Cutting Order Record List</h3>
                    </div>
                    <div class="d-flex justify-content-end mb-1">
                        <a href="javascript:void(0);" class="btn btn-success mb-2 d-none" id="btn_modal_create">Create</a>
                    </div>

                    <table class="table table-bordered table-hover" id="cutting_order_table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="">No. </th>
                                <th scope="col" class="">Serial Number</th>
                                <th width="10%" scope="col" class="">Created At</th>
                                <th width="10%" scope="col" class="">Status Lay</th>
                                <th width="10%" scope="col" class="">Status Cut</th>
                                <th width="10%" scope="col" class="">Action</th>
                            </tr>
                        </thead>
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
</script>

<script type="text/javascript">
    $(function (e) {
        $('#cutting_order_table').DataTable({
            processing: true,
            serverSide: true,
            // using parameter /cutting-group-data date start, date end, group id
            // ajax: "{{ route('cutting-ticket-detail-data', $id) }}",
            ajax: "{{ route('cutting-group-data', $id) }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'serial_number', name: 'serial_number', render: function(data, type, row) {
                    return '<a href="{{ url("/cutting-order") }}/'+row.id+'">'+data+'</a>';
                }},
                {data: 'created_at', name: 'created_at'},
                {data: 'status', name: 'status'},
                {data: 'status_cut', name: 'status_cut'},
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