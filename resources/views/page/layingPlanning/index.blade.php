@extends('layouts.master')

@section('title', 'Laying Planning')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="content-title text-center">
                        <h3>Laying Planning List</h3>
                    </div>
                    <!-- if(Auth::user()->hasRole('super_admin') || Auth::user()->hasRole('cutter')){ -->
                    <div class="d-flex justify-content-end mb-1">
                        @if(Auth::user()->hasRole('super_admin') || Auth::user()->hasRole('cutter'))
                        <a  href="{{ url('/laying-planning-create') }}" class="btn btn-success mb-2">Create</a>
                        @endif
                    </div>
                    <table class="table table-bordered table-hover" id="laying_planning_table">
                        <thead class="">
                            <tr class="text-center">
                                <th scope="col" width="10px">No. </th>
                                <th scope="col" width="12%">Serial Number</th>
                                <th scope="col">Style</th>
                                <th scope="col">Buyer</th>
                                <th scope="col">Color</th>
                                <th scope="col">Fabric Type</th>
                                <th scope="col" width="280px">Action</th>
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
            order: [],
            ajax: "{{ url('/laying-planning-data') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', className: 'text-center', orderable: false, searchable: false},
                {data: 'serial_number', name: 'laying_plannings.serial_number'},
                {data: 'style', name: 'styles.style'},
                {data: 'buyer', name: 'buyers.name'},
                {data: 'color', name: 'colors.color'},
                {data: 'fabric_type', name: 'fabric_types.description'},
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