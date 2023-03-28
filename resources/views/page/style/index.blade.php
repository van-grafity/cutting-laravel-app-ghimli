@extends('layouts.master')

@section('title', 'Style')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-end mb-1">
                        <a href="javascript:void(0);" class="btn btn-success mb-2" id="btn_modal_create" data-id='2'>Create</a>
                    </div>
                    <table class="table table-bordered table-hover" id="style_table">
                        <thead class="">
                            <tr>
                                <th scope="col" style="width: 20px">No</th>
                                <th scope="col" class="text-left">GL</th>
                                <th scope="col" class="text-left">Style</th>
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
                <h5 class="modal-title" id="modal_formLabel">Add Style</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('style.store') }}" method="POST" class="custom-validation" enctype="multipart/form-data" id="style_form">
                @csrf
                <div class="modal-body">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="gl" class="form-label">GL</label>
                            <select class="form-control select2" id="gl" name="gl" style="width: 100%;" data-placeholder="Choose GL">
                                <option value="">Choose GL</option>
                                @foreach ($gls as $gl)
                                    <option value="{{ $gl->id }}">{{ $gl->gl_number }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="style">Style</label>
                            <input type="text" class="form-control" id="style" name="style" placeholder="Enter style">
                        </div>
                        <div class="form-group">
                            <label for="style_desc" class="form-label">Description</label>
                            <textarea class="form-control" name="style_desc" id="style_desc" cols="30" rows="2" ></textarea>
                        </div>
                    </div>
                    <!-- END .card-body -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btn_submit">Add Style</button>
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
    
    $('.select2').select2({ 
        dropdownParent: $('#modal_form')
    });

    $('#btn_modal_create').click((e) => {
        $('#modal_formLabel').text("Add Style")
        $('#btn_submit').text("Add Style")
        $('#style_form').attr('action', create_url);
        $('#style_form').find('#gl').val('').trigger('change');
        $('#style_form').find("input[type=text], textarea").val("");
        $('#style_form').find('input[name="_method"]').remove();
        $('#modal_form').modal('show')
    })

    // Ketika opsi dipilih pada select2, panggil validasi untuk menghilangkan pesan error
    $("#gl").on("select2:close", function() {
        if ($(this).valid()) {
            $(this).removeClass("is-invalid");
            $(this).next(".invalid-feedback").remove();
        }
    });
})
</script>

<script type="text/javascript">
$(function (e) {

    // ## Datatable Initialize
    $('#style_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('/style-data') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'gl_number', name: 'gl_number'},
            {data: 'style', name: 'style'},
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
        gl: {
            required: true,
        },
        style: {
            required: true,
        },
        style_desc: {
            required: true,
        },
    };
    let messages = {
        gl: {
            required: "Please choose GL Number",
        },
        style: {
            required: "Please enter the style name",
        },
        style_desc: {
            required: "Please provide a description",
        },
    };
    let validator = $("#style_form").validate({
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
    const create_url ='{{ route("style.store",":id") }}';
    const edit_url ='{{ route("style.show",":id") }}';
    const update_url ='{{ route("style.update",":id") }}';
    const delete_url ='{{ route("style.destroy",":id") }}';

    async function edit_style(style_id) {
        let url_edit = edit_url.replace(':id',style_id);

        result = await get_using_fetch(url_edit);
        form = $('#style_form')
        form.append('<input type="hidden" name="_method" value="PUT">');
        $('#modal_formLabel').text("Edit Style");
        $('#btn_submit').text("Save");
        $('#modal_form').modal('show')

        let url_update = update_url.replace(':id',style_id);
        form.attr('action', url_update);
        form.find('#gl').val(result.gl_id).trigger('change');
        form.find('input[name="style"]').val(result.style);
        form.find('textarea[name="style_desc"]').val(result.description);
    }

    async function delete_style(style_id) {
        data = { title: "Are you sure?" };
        let confirm_delete = await swal_delete_confirm(data);
        if(!confirm_delete) { return false; };

        let url_delete = delete_url.replace(':id',style_id);
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