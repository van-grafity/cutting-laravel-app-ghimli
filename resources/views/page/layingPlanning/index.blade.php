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
                        <a class="btn btn-danger mb-2 mr-2" id="btn_modal_report" data-toggle="modal" data-target="#modal_report">Report</a>
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

<div class="modal fade" id="modal_report" tabindex="-1" role="dialog" aria-labelledby="modal_report" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modal_report">Report laying Planning</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="serial_number">Serial Number</label>
                <select class="form-control" id="serial_number" name="serial_number">
                    <option value="">-- Select Serial Number --</option>
                    @foreach ($data as $item)
                        <option value="<?= $item->serial_number ?>"><?= $item->serial_number ?></option>
                    @endforeach
                </select>
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <a href="{{ url('/laying-planning-report', $item->serial_number) }}" class="btn btn-primary">Report</a>
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