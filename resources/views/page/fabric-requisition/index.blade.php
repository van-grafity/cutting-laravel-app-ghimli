@extends('layouts.master')

@section('title', 'Fabric Requisition')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="content-title text-center">
                        <h3>Fabric Requisition List</h3>
                    </div>
                    <div class="d-flex justify-content-end mb-1">
                        <a href="javascript:void(0);" class="btn btn-success mb-2 d-none" id="btn_modal_create">Create</a>
                    </div>

                    <table class="table table-bordered table-hover" id="fabric_requisition_table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="">No. </th>
                                <th scope="col" class="">Serial Number</th>
                                <th scope="col" class="">P/O No</th>
                                <th width="10%" scope="col" class="">Status Cut</th>
                                <th width="16%" scope="col" class="">Action</th>
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
    const delete_url ='{{ route("fabric-requisition.destroy",":id") }}';
    
    async function delete_fabricRequisition(fabric_requisition_id) {

        data = { title: "Are you sure?" };
        let confirm_delete = await swal_delete_confirm(data);
        if(!confirm_delete) { return false; };

        let url_delete = delete_url.replace(':id',fabric_requisition_id);
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
        $('#fabric_requisition_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('/fabric-requisition-data') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'serial_number', name: 'serial_number'},
                {data: 'fabric_po', name: 'fabric_po'},
                {data: 'is_issue', name: 'is_issue'},
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
    
$(document).ready(function() {


});
</script>
@endpush