@extends('layouts.master')

@section('title', 'Laying Planning')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="content-title text-center">
                        <h3>Laying Planning List</h3>
                    </div>
                    <div class="d-flex justify-content-end mb-1">
                        <a  href="{{ url('/laying-planning-create') }}" class="btn btn-success mb-2">Create</a>
                    </div>
                    <table class="table table-bordered table-hover" id="laying_planning_table">
                        <thead class="">
                            <tr>
                                <th scope="col" width="20">No. </th>
                                <th scope="col" width="100">Serial Number</th>
                                <th scope="col">Style</th>
                                <th scope="col">Buyer</th>
                                <th scope="col">Color</th>
                                <th scope="col">Fabric Type</th>
                                <th scope="col" width="120">Action</th>
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
    })
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const delete_url ='{{ route("laying-planning.destroy",":id") }}';
    
    async function delete_layingPlanning(user_id) {

        if(!confirm("Apakah anda yakin ingin menghapus laying planning ini?")) {
            return false;
        }

        let url_delete = delete_url.replace(':id',user_id);
        let data_params = { token };

        result = await delete_using_fetch(url_delete, data_params)
        if(result.status == "success"){
            alert(result.message)
            laying_planning_id = $(this).attr('data-id');
            location.reload();
        } else {
            alert("Terjadi Kesalahan");
        }
    };
</script>

<script type="text/javascript">
    $(function (e) {
        $('#laying_planning_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('/laying-planning-data') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'serial_number', name: 'serial_number'},
                {data: 'style', name: 'style'},
                {data: 'buyer', name: 'buyer'},
                {data: 'color', name: 'color'},
                {data: 'fabric_type', name: 'fabric_type'},
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