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
                                <th width="1.8%" scope="col" class="">No. </th>
                                <th width="20%" scope="col" class="">Serial Number</th>
                                <th scope="col" class="">Color</th>
                                <th scope="col" class="">Style</th>
                                <th scope="col" class="">Fabric Type</th>
                                <th scope="col" class="">Fabric Cons</th>
                                <th width="6%" scope="col" class="">Created</th>
                                <th width="6%" scope="col" class="">Status Lay</th>
                                <th width="6%" scope="col" class="">Status Cut</th>
                                <th width="8%" scope="col" class="">Action</th>
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
                {data: 'serial_number', name: 'serial_number', render: function(data, type, row) {
                    return '<a href="{{ url("/cutting-order") }}/'+row.id+'">'+data+'</a>';
                }},
                {data: 'color', name: 'color'},
                {data: 'style', name: 'style'},
                {data: 'fabric_type', name: 'fabric_type'},
                {data: 'fabric_cons', name: 'fabric_cons'},
                {data: 'created_at', name: 'created_at'},
                {data: 'status', name: 'status'},
                {data: 'status_cut', name: 'status_cut'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
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