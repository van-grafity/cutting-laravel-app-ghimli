@extends('layouts.master')

@section('title', 'Fabric Type')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-end align-items-center mb-1">
                        <a href="javascript:void(0);" class="btn btn-success mb-2" id="btn_modal_create" data-id='2'>Create</a>
                    </div>
                    <table class="table table-bordered table-hover" id="fabric_type_table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="text-left">No. </th>
                                <th scope="col" class="text-left">Name</th>
                                <th scope="col" class="text-left">Description</th>
                                <th scope="col" style="width: 10%;" class="text-left">Action</th>
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
                <h5 class="modal-title" id="modal_formLabel">Add Fabric Type</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('fabric-type.store') }}" method="POST" class="custom-validation" enctype="multipart/form-data" id="fabricType_form">
                @csrf
                <div class="modal-body">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="fabricType_name">Name</label>
                            <input type="text" class="form-control" id="fabricType_name" name="name" placeholder="Enter name">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" name="description" id="description" cols="30" rows="3"></textarea>
                        </div>
                    </div>
                    <!-- END .card-body -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btn_submit">Add Fabric Type</button>
                </div>
            </form>
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

    $('#btn_modal_create').click((e) => {
        $('#modal_formLabel').text("Add Fabric Type")
        $('#btn_submit').text("Add Fabric Type")
        $('#fabricType_form').attr("action", create_url);
        $('#fabricType_form').find("input[type=text], textarea").val("");
        $('#fabricType_form').find('input[name="_method"]').remove();
        $('#modal_form').modal('show')
    })

    $('#modal_form').on('hidden.bs.modal', function () {
        $(this).find('.is-invalid').removeClass('is-invalid');
    });

})
</script>

<script type="text/javascript">
$(function (e) {

    // ## Datatable Initialize
    $('#fabric_type_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('/fabric-type-data') }}",
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


    // ## Form Validation
    let rules = {
        name: {
            required: true,
        },
        description: {
            required: true,
        },
    };
    let messages = {
        name: {
            required: "Please enter the name",
        },
        description: {
            required: "Please provide a description",
        },
    };
    let validator = $("#fabricType_form").validate({
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
});
</script>

<script type="text/javascript">
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const create_url ='{{ route("fabric-type.store",":id") }}';
    const edit_url ='{{ route("fabric-type.show",":id") }}';
    const update_url ='{{ route("fabric-type.update",":id") }}';
    const delete_url ='{{ route("fabric-type.destroy",":id") }}';


    async function edit_fabricType(user_id) {
        let url_edit = edit_url.replace(':id',user_id);

        result = await get_using_fetch(url_edit);
        form = $('#fabricType_form')
        form.append('<input type="hidden" name="_method" value="PUT">');
        $('#modal_formLabel').text("Edit Fabric Type");
        $('#btn_submit').text("Save");
        $('#modal_form').modal('show')

        let url_update = update_url.replace(':id',user_id);
        form.attr('action', url_update);
        form.find('input[name="name"]').val(result.name);
        form.find('textarea[name="description"]').val(result.description);
    }

    async function delete_fabricType(user_id) {
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

</script>
@endpush