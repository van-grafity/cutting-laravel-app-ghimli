@extends('layouts.master')

@section('title', 'Fabric Usage')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-end mb-1">
                        <a href="javascript:void(0);" class="btn btn-success mb-2" id="btn_modal_create" data-id='2'>Create</a>
                    </div>

                    <table class="table table-bordered table-hover" id="fabric_usage_table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="text-left">No. </th>
                                <th scope="col" class="text-left">Portion</th>
                                <th scope="col" class="text-left">Content</th>
                                <th scope="col" class="text-left">Type</th>
                                <th scope="col" class="text-left">Type Description</th>
                                <th scope="col" class="text-left">Quantity Consumed</th>
                                <th scope="col" class="text-left">Action</th>
                            </tr>
                        </thead>
                    </table>
                    <!-- <tbody></tbody> -->
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
                <h5 class="modal-title" id="modal_formLabel">Add Fabric Usage</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('fabric-usage.store') }}" method="POST" class="custom-validation" enctype="multipart/form-data" id="fabricUsage_form">
                @csrf
                <div class="modal-body">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="portion">Portion</label>
                            <input type="text" class="form-control" id="portion" name="portion" placeholder="Enter portion">
                        </div>
                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea class="form-control" name="content" id="content" cols="30" rows="2"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="type">Type</label>
                            <input type="text" class="form-control" id="type" name="type" placeholder="Enter type">
                        </div>
                        <div class="form-group">
                            <label for="type_description">Type Description</label>
                            <textarea class="form-control" name="type_description" id="type_description" cols="30" rows="2"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="quantity">Quantity Consumed</label>
                            <input type="number" class="form-control" id="quantity_consumed" name="quantity_consumed" placeholder="Enter quantity" step="0.01">
                        </div>
                    </div>
                    <!-- END .card-body -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btn_submit">Add Fabric Usage</button>
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
        $('#modal_formLabel').text("Add Fabric Usage")
        $('#btn_submit').text("Add Fabric Usage")
        $('#fabricUsage_form').attr("action", create_url);
        $('#fabricUsage_form').find("input[type=text], textarea").val("");
        $('#fabricUsage_form').find('input[name="_method"]').remove();
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
    $('#fabric_usage_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('/fabric-usage-data') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'portion', name: 'portion'},
            {data: 'content', name: 'content'},
            {data: 'type', name: 'type'},
            {data: 'type_description', name: 'type_description'},
            {data: 'qty_consumed', name: 'qty_consumed'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        lengthChange: true,
        searching: true,
        autoWidth: false,
        responsive: true,
    });


    // ## Form Validation
    let rules = {
        portion: {
            required: true,
        },
        content: {
            required: true,
        },
        type: {
            required: true,
        },
        type_description: {
            required: true,
        },
        quantity_consumed: {
            required: true,
        },
    };
    let messages = {
        portion: {
            required: "Please enter portion",
        },
        content: {
            required: "Please provide a content",
        },
        type: {
            required: "Please enter type",
        },
        type_description: {
            required: "Please provide type description",
        },
        quantity_consumed: {
            required: "Please enter quantity consumed",
        },
    };
    let validator = $("#fabricUsage_form").validate({
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
    const create_url ='{{ route("fabric-usage.store",":id") }}';
    const edit_url ='{{ route("fabric-usage.show",":id") }}';
    const update_url ='{{ route("fabric-usage.update",":id") }}';
    const delete_url ='{{ route("fabric-usage.destroy",":id") }}';


    async function edit_fabricUsage(user_id) {
        let url_edit = edit_url.replace(':id',user_id);

        result = await get_using_fetch(url_edit);
        form = $('#fabricUsage_form')
        form.append('<input type="hidden" name="_method" value="PUT">');
        $('#modal_formLabel').text("Edit Fabric Usage");
        $('#btn_submit').text("Save");
        $('#modal_form').modal('show')

        let url_update = update_url.replace(':id',user_id);
        form.attr('action', url_update);
        form.find('input[name="portion"]').val(result.portion);
        form.find('textarea[name="content"]').val(result.content);
        form.find('input[name="type"]').val(result.type);
        form.find('textarea[name="type_description"]').val(result.type_description);
        form.find('input[name="quantity_consumed"]').val(result.quantity_consumed);
    }

    async function delete_fabricUsage(user_id) {
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