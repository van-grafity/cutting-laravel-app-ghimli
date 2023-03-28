@extends('layouts.master')

@section('title', 'Size')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-end mb-1">
                        <a href="javascript:void(0);" class="btn btn-success mb-2" id="btn_modal_create" data-id='2'>Create</a>
                    </div>
                    <table class="table table-bordered table-hover" id="size_table">
                        <thead class="">
                            <tr>
                                <th scope="col" style="width: 20px">No</th>
                                <th scope="col" class="text-left">Size</th>
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
                <h5 class="modal-title" id="modal_formLabel">Add Size</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('size.store') }}" method="POST" class="custom-validation" enctype="multipart/form-data" id="size_form">
                @csrf
                <div class="modal-body">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="size">Size</label>
                            <input type="text" class="form-control" id="size" name="size" placeholder="Enter size">
                        </div>
                    </div>
                    <!-- END .card-body -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btn_submit">Add Size</button>
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
        $('#modal_formLabel').text("Add Size")
        $('#btn_submit').text("Add Size")
        $('#size_form').attr('action', create_url);
        $('#size_form').find("input[type=text], textarea").val("");
        $('#size_form').find('input[name="_method"]').remove();
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
    $('#size_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('/size-data') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'size', name: 'size'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        lengthChange: true,
        searching: true,
        autoWidth: false,
        responsive: true,
    });


    // ## Form Validation
    let rules = {
        size: {
            required: true,
        },
    };
    let messages = {
        size: {
            required: "Please enter the size",
        },
    };
    let validator = $("#size_form").validate({
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
    const create_url ='{{ route("size.store",":id") }}';
    const edit_url ='{{ route("size.show",":id") }}';
    const update_url ='{{ route("size.update",":id") }}';
    const delete_url ='{{ route("size.destroy",":id") }}';

    async function edit_size (size_id) {
        let url_edit = edit_url.replace(':id',size_id);

        result = await get_using_fetch(url_edit);
        form = $('#size_form')
        form.append('<input type="hidden" name="_method" value="PUT">');
        $('#modal_formLabel').text("Edit Size");
        $('#btn_submit').text("Save");
        $('#modal_form').modal('show')

        let url_update = update_url.replace(':id',size_id);
        form.attr('action', url_update);
        form.find('input[name="size"]').val(result.size);
    }

    async function delete_size (size_id) {
        data = { title: "Are you sure?" };
        let confirm_delete = await swal_delete_confirm(data);
        if(!confirm_delete) { return false; };

        let url_delete = delete_url.replace(':id',size_id);
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