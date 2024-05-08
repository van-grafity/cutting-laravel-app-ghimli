
@extends('layouts.master')
@section('title', 'Cutting Groups')
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex p-0">
                <h3 class="card-title p-3 my-auto"> Group List </h3>

                <div class="ml-auto p-3">
                    @can('manage')
                        <a href="javascript:void(0)" class="btn btn-default mr-2" id="btn_sync_old_data" onclick="sync_old_data()">Sync Old Data</a>
                        <a href="javascript:void(0)" class="btn btn-success" id="btn_modal_create" onclick="show_modal_create('modal_group')">Create</a>
                    @endcan
                </div>

            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="mb-3 text-right">
                    <button id="reload_table_btn" class="btn btn-sm btn-info">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
                <table id="group_table" class="table table-bordered table-hover text-center">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th width="250">Group</th>
                            <th width="">Description</th>
                            <th width="150">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

    </div>
    <!-- /.col -->
</div>

<!-- Modal Add and Edit Group -->
<div class="modal fade" id="modal_group" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="post" onsubmit="stopFormSubmission(event)">
                <input type="hidden" name="edit_group_id" value="" id="edit_group_id">

                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Add New Group</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="group" class="col-form-label">Group</label>
                        <input type="text" class="form-control" id="group" name="group" required>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-form-label">Description</label>
                        <textarea class="form-control" name="description" id="description" cols="30" rows="2" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-submit" onclick="submitForm('modal_group')">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Add and Edit Group -->
@endsection

@section('js')
<script type="text/javascript">

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const column_visible = '{{ $can_manage }}';
    
    // ## URL List
    const show_url = "{{ route('cutting-group.show',':id') }}";
    const store_url = "{{ route('cutting-group.store') }}";
    const update_url = "{{ route('cutting-group.update',':id') }}";
    const delete_url = "{{ route('cutting-group.destroy',':id') }}";
    const dtable_url = "{{ route('cutting-group.dtable') }}";
    const sync_old_data_url = "{{ route('cutting-group.sync-old-data') }}";

    const show_modal_create = (modal_element_id) => {
        let modal_data = {
            modal_id : modal_element_id,
            title : "Add New Group",
            btn_submit : "Save",
            form_action_url : store_url,
        }
        clear_form(modal_data);
        $(`#${modal_element_id}`).modal('show');
    }

    const show_modal_edit = async (modal_element_id, group_id) => {
        let modal_data = {
            modal_id : modal_element_id,
            title : "Edit Group",
            btn_submit : "Save",
            form_action_url : update_url.replace(':id',group_id),
        }
        clear_form(modal_data);
        
        fetch_data = {
            url: show_url.replace(':id',group_id),
            method: "GET",
            token: token,
        }
        result = await using_fetch_v2(fetch_data);
        group_data = result.data.group

        $('#group').val(group_data.group);
        $('#description').val(group_data.description);
        $('#edit_group_id').val(group_data.id);
        
        $(`#${modal_element_id}`).modal('show');
    }

    const submitForm = async (modal_id) => {
        try {
            let modal = document.getElementById(modal_id);
            let submit_btn = modal.querySelector('.btn-submit');
            submit_btn.setAttribute('disabled', 'disabled');
            
            let form = modal.querySelector('form');
            let formData = getFormData(form);

            if (!$(form).valid()) {
                submit_btn.removeAttribute('disabled');
                return false;
            }

            if(!formData.edit_group_id) {
                // ## kalau tidak ada group id berarti STORE dan Method nya POST
                fetch_data = {
                    url: store_url,
                    method: "POST",
                    data: formData,
                    token: token,
                }
            } else {
                // ## kalau ada group id berarti UPDATE dan Method nya PUT
                fetch_data = {
                    url: update_url.replace(':id',formData.edit_group_id),
                    method: "PUT",
                    data: formData,
                    token: token,
                }
            }

            const response = await using_fetch_v2(fetch_data);
            if(response.status == 'success') {
                swal_info({ title: response.message })
                
                reload_dtable();
            } else {
                swal_failed({ title: response.message })
            }

            submit_btn.removeAttribute('disabled');

        } catch (error) {
            console.error("Error:", error);
        }

        $(`#${modal_id}`).modal('hide');
    }

    const show_modal_delete = async (group_id) => {
        swal_data = {
            title: "Are you Sure?",
            text: "Want to delete the group",
            icon: "warning",
            confirmButton: "Delete",
            confirmButtonClass: "btn-danger",
            cancelButtonClass: "btn-secondary"
        };
        let confirm_delete = await swal_confirm(swal_data);
        if(!confirm_delete) { return false; };

        fetch_data = {
            url: delete_url.replace(':id',group_id),
            method: "DELETE",
            token: token,
        }
        result = await using_fetch_v2(fetch_data);

        if(result.status == "success"){
            swal_info({
                title : result.message,
            });

            reload_dtable();
            
        } else {
            swal_failed({ title: result.message });
        }
    }

    const reload_dtable = () => {
        $('#reload_table_btn').trigger('click');
    }
    
    const sync_old_data = async () => {
        fetch_data = {
            url: sync_old_data_url,
            method: "GET",
            token: token,
        }
        result = await using_fetch_v2(fetch_data);

        if(result.status == "success"){
            swal_info({
                title : result.message,
            });

            reload_dtable();
            
        } else {
            swal_failed({ title: result.message });
        }

        $('#reload_table_btn').trigger('click');
    }

</script>

<script type="text/javascript">
    let group_table = $('#group_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: dtable_url,
            beforeSend: function() {
                // ## Tambahkan kelas dimmed-table sebelum proses loading dimulai
                $('#group_table').addClass('dimmed-table').append('<div class="datatable-overlay"></div>');
            },
            complete: function() {
                // ## Hapus kelas dimmed-table setelah proses loading selesai
                $('#group_table').removeClass('dimmed-table').find('.datatable-overlay').remove();
            },
        },
        order: [],
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex'},
            { data: 'group', name: 'group'},
            { data: 'description', name: 'description'},
            { data: 'action', name: 'action', visible: column_visible },
        ],
        columnDefs: [
            { targets: [0,-1], orderable: false, searchable: false },
        ],
        
        paging: true,
        responsive: true,
        lengthChange: true,
        searching: true,
        autoWidth: false,
        orderCellsTop: true,
        searchDelay: 500,
    })

    $('#reload_table_btn').on('click', function(event) {
        $(this).addClass('loading').attr('disabled',true);
        group_table.ajax.reload(function(json){
            $('#reload_table_btn').removeClass('loading').attr('disabled',false);
        });
    });

    let validator = $('#modal_group form').validate({
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
    });

</script>
@stop