
@extends('layouts.master')
@section('title', $title)
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex p-0">
                <h3 class="card-title p-3 my-auto"> Token List </h3>

                <div class="ml-auto p-3">
                    @can('manage')
                        <a href="javascript:void(0)" class="btn btn-danger" id="btn_modal_create" onclick="show_modal_revoke('modal_revoke_token')">Revoke Token</a>
                        <a href="javascript:void(0)" class="btn btn-success" id="btn_modal_revoke Token" onclick="show_modal_create('modal_token')">Create</a>
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
                <table id="token_table" class="table table-bordered table-hover text-center">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th width="">User</th>
                            <th width="">Token Name</th>
                            <th width="">Abilities</th>
                            <th width="">Last Used At</th>
                            <th width="">Expires At</th>
                            <th width="">Created At</th>
                            <th width="">Action</th>
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

<!-- Modal Add and Edit Token -->
<div class="modal fade" id="modal_token" tabindex="-1" role="dialog" aria-labelledby="modal_token_label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="post" onsubmit="stopFormSubmission(event)">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_token_label">Add New Token</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="user" class="col-form-label">User</label>
                        <select name="user" id="user" class="form-control select2" required data-placeholder="Choose User">
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="token_name" class="col-form-label">Token Name</label>
                        <input type="text" class="form-control" id="token_name" name="token_name" required placeholder="cutting_app">
                    </div>
                    <div class="form-group">
                        <label for="expires_in" class="col-form-label">Expires in (Hours)</label>
                        <input type="number" class="form-control" id="expires_in" name="expires_in" required placeholder="24">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-submit" onclick="submitForm('modal_token')">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Add and Edit Token -->


<!-- Modal Show Token -->
<div class="modal fade" id="modal_new_token" tabindex="-1" role="dialog" aria-labelledby="modal_new_token_label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_new_token_label">New Token</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning" role="alert">
                    <strong>Important:</strong> Please copy the token below. You will only be able to see it once.
                </div>
                <div class="input-group mb-3">
                    <input type="text" id="new_token" class="form-control" value="" readonly>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" onclick="copyToken()">
                            <i class="fa fa-copy"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Done</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Show Token -->


<!-- Modal Revoke Token -->
<div class="modal fade" id="modal_revoke_token" tabindex="-1" role="dialog" aria-labelledby="modal_revoke_token_label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="post" onsubmit="stopFormSubmission(event)">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_revoke_token_label">Revoke Token</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="user_revoke" class="col-form-label">User</label>
                        <select name="user_revoke" id="user_revoke" class="form-control select2" required data-placeholder="Choose User Revoke">
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="token_name_revoke" class="col-form-label">Token Name</label>
                        <input type="text" class="form-control" id="token_name_revoke" name="token_name_revoke" placeholder="cutting_app">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger btn-submit" onclick="submitForm('modal_revoke_token')">Revoke</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Revoke Token -->


@endsection

@section('js')
<script type="text/javascript">

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const column_visible = '{{ $can_manage }}';
    
    // ## URL List
    const store_url = "{{ route('personal-access-token.store') }}";
    const delete_url = "{{ route('personal-access-token.destroy',':id') }}";
    const dtable_url = "{{ route('personal-access-token.dtable') }}";
    const fetch_select_user_url = "{{ route('fetch-select.user') }}";
    const revoke_token_url = "{{ route('personal-access-token.revoke-token') }}";


    const show_modal_create = (modal_element_id) => {
        let modal_data = {
            modal_id : modal_element_id,
            title : "Add New Token",
            btn_submit : "Save",
            form_action_url : store_url,
        }
        clear_form(modal_data);
        $(`#${modal_element_id}`).modal('show');
    }

    const show_modal_revoke = (modal_element_id) => {
        let modal_data = {
            modal_id : modal_element_id,
            title : "Revoke Token",
            btn_submit : "Revoke",
            form_action_url : revoke_token_url,
        }
        clear_form(modal_data);
        $(`#${modal_element_id}`).modal('show');
    }

    const submitForm = async (modal_id) => {
        let modal = document.getElementById(modal_id);
        let submit_btn = modal.querySelector('.btn-submit');
        try {
            submit_btn.setAttribute('disabled', 'disabled');
            
            let form = modal.querySelector('form');
            let formData = getFormData(form);

            if (!$(form).valid()) {
                submit_btn.removeAttribute('disabled');
                return false;
            }

            fetch_data = {
                url: form.action,
                method: "POST",
                data: formData,
                token: token,
            }

            const response = await using_fetch_v2(fetch_data);
            if(response.status == 'success') {
                if(modal_id == 'modal_token'){
                    $('#new_token').val(response.data.token);
                    $(`#modal_new_token`).modal('show');
                } else {
                    swal_info({ title: response.message })
                }
                reload_dtable();
            } else {
                swal_failed({ title: response.message })
            }

            
        } catch (error) {
            console.error("Error:", error);
        }

        submit_btn.removeAttribute('disabled');
        $(`#${modal_id}`).modal('hide');
    }

    const show_modal_delete = async (token_id) => {
        swal_data = {
            title: "Are you Sure?",
            text: "Want to delete the token",
            icon: "warning",
            confirmButton: "Delete",
            confirmButtonClass: "btn-danger",
            cancelButtonClass: "btn-secondary"
        };
        let confirm_delete = await swal_confirm(swal_data);
        if(!confirm_delete) { return false; };

        fetch_data = {
            url: delete_url.replace(':id',token_id),
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

    const copyToken = () => {
        const tokenText = document.getElementById('new_token').value;
        if (navigator.clipboard) {
            navigator.clipboard.writeText(tokenText).then(() => {
                toastr.success('Token copied to clipboard!')
            }).catch(err => {
                console.error('Could not copy text: ', err);
            });
        } else {
            // Fallback if navigator.clipboard is not available
            const textArea = document.createElement('textarea');
            textArea.value = tokenText;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            toastr.success('Token copied to clipboard!')

        }
    }

</script>

<script type="text/javascript">
    let token_table = $('#token_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: dtable_url,
            beforeSend: function() {
                // ## Tambahkan kelas dimmed-table sebelum proses loading dimulai
                $('#token_table').addClass('dimmed-table').append('<div class="datatable-overlay"></div>');
            },
            complete: function() {
                // ## Hapus kelas dimmed-table setelah proses loading selesai
                $('#token_table').removeClass('dimmed-table').find('.datatable-overlay').remove();
            },
        },
        order: [],
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex'},
            { data: 'user', name: 'users.name'},
            { data: 'name', name: 'name'},
            { data: 'abilities', name: 'abilities'},
            { data: 'last_used_at', name: 'personal_access_tokens.last_used_at'},
            { data: 'expires_at', name: 'personal_access_tokens.expires_at'},
            { data: 'created_at', name: 'personal_access_tokens.created_at'},
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
    })

    $('#reload_table_btn').on('click', function(event) {
        $(this).addClass('loading').attr('disabled',true);
        token_table.ajax.reload(function(json){
            $('#reload_table_btn').removeClass('loading').attr('disabled',false);
        });
    });

    let validator = $('#modal_token form').validate({
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");
            element.closest(".form-group").append(error);

            // ## khusus untuk select2
            if (element.hasClass('select2-hidden-accessible')) {
                error.insertAfter(element.next('span.select2-container'));
            }

            // ## validasi error pada select2
            if (!$(element).val()) {
                $(element).parent().find('.select2-container').addClass('select2-container--error');
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        },
    });

    let validator_revoke_token = $('#modal_revoke_token form').validate({
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");
            element.closest(".form-group").append(error);

            // ## khusus untuk select2
            if (element.hasClass('select2-hidden-accessible')) {
                error.insertAfter(element.next('span.select2-container'));
            }

            // ## validasi error pada select2
            if (!$(element).val()) {
                $(element).parent().find('.select2-container').addClass('select2-container--error');
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        },
    });

    let $user_select2 = $('#user.select2').select2({
        dropdownParent: $('#modal_token'),
        ajax: {
            url: fetch_select_user_url,
            dataType: 'json',
            delay: 300,
            data: function (params) {
                var query = {
                    search: params.term,
                }
                return query;
            },
            processResults: function (fetch_result) {
                return {
                    results: fetch_result.data.items,
                };
            },
        }
    });

    let $user_revoke_select2 = $('#user_revoke.select2').select2({
        dropdownParent: $('#modal_revoke_token'),
        ajax: {
            url: fetch_select_user_url,
            dataType: 'json',
            delay: 300,
            data: function (params) {
                var query = {
                    search: params.term,
                }
                return query;
            },
            processResults: function (fetch_result) {
                return {
                    results: fetch_result.data.items,
                };
            },
        }
    });

</script>
@stop