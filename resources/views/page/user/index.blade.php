@extends('layouts.master')

@section('title', 'User')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-end mb-1">
                        <a href="javascript:void(0);" class="btn btn-success mb-2" id="btn_modal_create" data-id='2'>Create</a>
                    </div>
                    <table class="table table-bordered table-hover" id="user_table">
                        <thead class="">
                            <tr>
                                <th scope="col" style="width: 20px">No</th>
                                <th scope="col" class="text-left">User</th>
                                <th scope="col" class="text-left">Email</th>
                                <th scope="col" class="text-left">Role</th>
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
                <h5 class="modal-title" id="modal_formLabel">Add User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('user-management.store') }}" method="POST" class="custom-validation" enctype="multipart/form-data" id="user_form">
                @csrf
                <div class="modal-body">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter name">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="Enter email">
                        </div>
                        <div class="form-group">
                            <label for="gl" class="form-label">Role</label>
                            <select class="form-control select2" id="role" name="role" style="width: 100%;" data-placeholder="Choose Role">
                                <option value="">Choose Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btn_submit">Add User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">

$(document).ready(function(){
    $('.select2').select2({ 
        dropdownParent: $('#modal_form')
    });

    $('#btn_modal_create').click((e) => {
        $('#modal_formLabel').text("Add User")
        $('#btn_submit').text("Add User")
        $('#user_form').attr('action', create_url);
        $('#user_form').find('#role').val('').trigger('change');
        $('#user_form').find("input[type=text], textarea").val("");
        $('#user_form').find('input[name="_method"]').remove();
        $('#modal_form').modal('show')
    })
})
</script>

<script type="text/javascript">
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const create_url ='{{ route("user-management.store",":id") }}';
    const edit_url ='{{ route("user-management.show",":id") }}';
    const update_url ='{{ route("user-management.update",":id") }}';
    const delete_url ='{{ route("user-management.destroy",":id") }}';

    async function edit_user (user_id) {
        let url_edit = edit_url.replace(':id',user_id);

        result = await get_using_fetch(url_edit);
        form = $('#user_form')
        form.append('<input type="hidden" name="_method" value="PUT">');
        $('#modal_formLabel').text("Edit User");
        $('#btn_submit').text("Save");
        $('#modal_form').modal('show')

        let url_update = update_url.replace(':id',user_id);
        form.attr('action', url_update);
        form.find('#role').val(result.roles[0].name).trigger('change');
        form.find('input[name="name"]').val(result.name);
        form.find('input[name="email"]').val(result.email);
    }

    async function delete_user (user_id) {
        if(!confirm("Apakah anda yakin ingin menghapus User ini?")) { return false; };

        let url_delete = delete_url.replace(':id',user_id);
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
        $('#user_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('/user-data') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'role', name: 'role'},
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