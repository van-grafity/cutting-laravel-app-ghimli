@extends('layouts.master')

@section('title', 'User')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex p-0">
                    <h3 class="card-title p-3 my-auto"> User List </h3>

                    <div class="ml-auto p-3">
                        <a href="javascript:void(0)" class="btn btn-success " id="btn_modal_create" onclick="show_modal_create('modal_user')">Create</a>
                    </div>
                </div>
                <div class="card-body">
                    <table id="user_table" class="table table-bordered table-hover text-center">
                        <thead>
                            <tr>
                                <th width="25">No</th>
                                <th width="" class="text-center">Name</th>
                                <th width="" class="text-center">Email</th>
                                <th width="">Department</th>
                                <th width="">Role</th>
                                <th width="">Created Date</th>
                                <th width="250">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Modal Section -->
<div class="modal fade" id="modal_user" tabindex="-1" role="dialog" aria-labelledby="modal_userLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_userLabel">Add User</h5>
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
                        <div class="form-group">
                            <label for="gl" class="form-label">Department</label>
                            <select class="form-control select2" id="department" name="department" style="width: 100%;" data-placeholder="Choose Role">
                                <option value="">Choose Department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->department }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-submit" id="btn_submit">Add User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')


<script type="text/javascript">
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const store_url ='{{ route("user-management.store") }}';
    const show_url ='{{ route("user-management.show",":id") }}';
    const update_url ='{{ route("user-management.update",":id") }}';
    const delete_url ='{{ route("user-management.destroy",":id") }}';
    const reset_password_url ='{{ route("user-management.reset",":id") }}';
    const dtable_url ='{{ route("user-management.dtable") }}';


    const show_modal_create = (modal_element_id) => {
        let modal_data = {
            modal_id : modal_element_id,
            title : "Add New User",
            btn_submit : "Add User",
            form_action_url : store_url,
        }
        clear_form(modal_data);

        $('#user_form input[name="_token"]').val(token)
        $('#user_form').find('input[name="_method"]').remove();

        $(`#${modal_element_id}`).modal('show');
    }

    const show_modal_edit = async (modal_element_id, user_id) => {
        let modal_data = {
            modal_id : modal_element_id,
            title : "Edit User",
            btn_submit : "Save",
            form_action_url : update_url.replace(':id', user_id),
            method: 'POST'
        }
        clear_form(modal_data);
        
        fetch_data = {
            url: show_url.replace(':id', user_id),
            method: "GET",
            token: token,
        }
        result = await using_fetch_v2(fetch_data);
        user_data = result.data.user;

        $('#name').val(user_data.name);
        $('#email').val(user_data.email);
        $('#department').val(user_data.department_id).trigger('change');
        $('#role').val(user_data.role).trigger('change');
        $('#edit_user_id').val(user_data.id);

        $('#user_form input[name="_token"]').val(token)
        $('#user_form').find('input[name="_method"]').remove();
        $('#user_form').append('<input type="hidden" name="_method" value="PUT">');

        
        $(`#${modal_element_id}`).modal('show');
    }

    async function delete_user(user_id) {
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
    }

    async function reset_user(user_id) {
        data = { title: "Want to Reset Password?" };
        let confirm_delete = await swal_delete_confirm(data);
        if(!confirm_delete) { return false; };

        let url_reset_password = reset_password_url.replace(':id',user_id);
        let data_params = { 
            token,
            body: { id: user_id }
         };

        result = await using_fetch(url_reset_password, data_params, "PUT");
        if(result.status == "success"){
            swal_info({ title : result.message });
        } else {
            swal_failed({ title: result.message });
        }
    }
</script>

<script type="text/javascript">

    $(document).ready(function(){

        // ## Show Flash Message
        let session = {!! json_encode(session()->all()) !!};
        show_flash_message(session);

        $('.select2').select2({ 
            dropdownParent: $('#modal_user')
        });

        $('#modal_user').on('hidden.bs.modal', function () {
            $(this).find('.is-invalid').removeClass('is-invalid');
        });
    })
</script>

<script type="text/javascript">
$(function (e) {

    // ## Datatable Initialize
    let user_table = $('#user_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: dtable_url,
        order: [],
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'name', name: 'name', className:'text-left'},
            { data: 'email', name: 'email', className:'text-left'},
            { data: 'department', name: 'department' },
            { data: 'role', name: 'role' },
            { data: 'created_date', name: 'users.created_at' },
            { data: 'action', name: 'action' },
        ],
        columnDefs: [
            { targets: [0, -1], orderable: false, searchable: false }, 
        ],
        lengthChange: true,
        searching: true,
        autoWidth: false,
        responsive: true,
    });


    // ## Form Validation
    let rules = {
        name: {
            required: true,
        },
        email: {
            required: true,
            email: true,
        },
        role: {
            required: true,
        }
    };
    let messages = {
        name: {
            required: "Please enter the user's name",
        },
        email: {
            required: "Please provide an email address",
            email: "must be an email format",
        },
        role: {
            required: "Please select the role",
        },
    };
    let validator = $("#user_form").validate({
        rules: rules,
        messages: messages,
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");
            element.closest(".form-group").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    // Ketika opsi dipilih pada select2, panggil validasi untuk menghilangkan pesan error
    $("#role").on("select2:close", function() {
        if ($(this).valid()) {
            $(this).removeClass("is-invalid");
            $(this).next(".invalid-feedback").remove();
        }
    });
    
});
</script>

@endpush