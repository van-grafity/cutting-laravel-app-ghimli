@extends('layouts.master')

@section('title', 'Remark')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-end mb-1">
                        <a href="javascript:void(0);" class="btn btn-success mb-2" id="btn_modal_create" data-id='2'>Create</a>
                    </div>
                    <table class="table table-bordered table-hover" id="remark_table">
                        <thead class="">
                            <tr>
                                <th scope="col" style="width: 30px;">No</th>
                                <th scope="col" class="text-left">Name</th>
                                <th scope="col" class="text-left">Description</th>
                                <th scope="col" class="text-left">Action</th>
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

<!-- Modal Section -->
<div class="modal fade" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="modal_formLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_formLabel">Add Remark</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('remark.store') }}" method="POST" class="custom-validation" enctype="multipart/form-data" id="remark_form">
                @csrf
                <div class="modal-body">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter name">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" name="description" id="description" cols="30" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btn_submit">Add Remark</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">

$(document).ready(function(){
    $('#btn_modal_create').click((e) => {
        $('#modal_formLabel').text("Add Remark")
        $('#btn_submit').text("Add Remark")
        $('#remark_form').attr('action', create_url);
        $('#remark_form').find("input[type=text], textarea").val("");
        $('#remark_form').find('input[name="_method"]').remove();
        $('#modal_form').modal('show')
    })
})
</script>

<script type="text/javascript">
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const create_url ='{{ route("remark.store",":id") }}';
    const edit_url ='{{ route("remark.show",":id") }}';
    const update_url ='{{ route("remark.update",":id") }}';
    const delete_url ='{{ route("remark.destroy",":id") }}';

    async function edit_remark(remark_id) {
        let url_edit = edit_url.replace(':id',remark_id);

        result = await get_using_fetch(url_edit);
        form = $('#remark_form')
        form.append('<input type="hidden" name="_method" value="PUT">');
        $('#modal_formLabel').text("Edit Remark");
        $('#btn_submit').text("Save");
        $('#modal_form').modal('show')

        let url_update = update_url.replace(':id',remark_id);
        form.attr('action', url_update);
        form.find('input[name="name"]').val(result.name);
        form.find('textarea[name="description"]').val(result.description);
    }

    async function delete_remark(remark_id) {
        if(!confirm("Apakah anda yakin ingin menghapus Remark ini?")) { return false; };

        let url_delete = delete_url.replace(':id',remark_id);
        let data_params = { token };

        result = await delete_using_fetch(url_delete, data_params)
        if(result.status == "success"){
            alert(result.message)
            location.reload();
        } else {
            console.log(result.message);
            alert("Terjadi Kesalahan");
        }
    }
</script>

<script type="text/javascript">
    $(function (e) {
        $('#remark_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('/remark-data') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name'},
                {data: 'description', name: 'description'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            lengthChange: true,
            searching: true,
            autoWidth: false,
            responsive: true,
        });
    });
</script>
@endpush