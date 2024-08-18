@extends('layouts.master')

@section('title', 'Summary by Group')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="content-title text-center">
                        <h3>Summary by Group</h3>
                    </div>
                    <table class="table table-bordered table-hover" id="laying_planning_table">
                        <thead class="">
                            <tr>
                                <th scope="col" width="20">No. </th>
                                <th scope="col" width="100">GL No.</th>
                                <th scope="col">Style</th>
                                <th scope="col">Buyer</th>
                                <th scope="col">Color</th>
                                <th scope="col">Fabric Type</th>
                                <th scope="col">Delivery Date</th>
                                <th scope="col">Plan Date</th>
                                <th scope="col">Action</th>
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
    $(document).ready(function(){
        // ## Show Flash Message
        let session = {!! json_encode(session()->all()) !!};
        show_flash_message(session);
    })
    
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const delete_url ='{{ route("laying-planning.destroy",":id") }}';
    
    async function delete_layingPlanning(user_id) {

        data = { title: "Are you sure?" };
        let confirm_delete = await swal_delete_confirm(data);
        if(!confirm_delete) { return false; };

        let url_delete = delete_url.replace(':id',user_id);
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
        $('#laying_planning_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('/subcon-cutting-data') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'gl_number', name: 'gl_number', render: function(data, type, row) {
                    return '<a href="{{ url("/subcon-cutting") }}/'+row.id+'">'+data+'</a>';
                }},
                {data: 'style', name: 'style'},
                {data: 'buyer', name: 'buyer'},
                {data: 'color', name: 'color'},
                {data: 'fabric_type', name: 'fabric_type'},
                {data: 'delivery_date', name: 'delivery_date'},
                {data: 'plan_date', name: 'plan_date'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            lengthChange: true,
            searching: true,
            autoWidth: false,
            responsive: true,
        });
    });
</script>

@endpush('js')