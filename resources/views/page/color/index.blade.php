@extends('layouts.master')

@section('title', 'Color')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-end mb-1">
                        <a href="javascript:void(0);" class="btn btn-success mb-2" id="btn_modal_create" data-id='2'>Create</a>
                    </div>
                    <table class="table table-bordered table-hover" id="color_table">
                        <thead class="">
                            <tr>
                                <th scope="col" style="width: 70px;">#</th>
                                <th scope="col" class="text-left">Color</th>
                                <th scope="col" class="text-left">Code</th>
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
                <h5 class="modal-title" id="modal_formLabel">Add Color</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('color.store') }}" method="POST" class="custom-validation" enctype="multipart/form-data" id="color_form">
                @csrf
                <div class="modal-body">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="color_name">Color</label>
                            <input type="text" class="form-control" id="color_name" name="color" placeholder="Enter name">
                        </div>
                        <div class="form-group">
                            <label for="color_code">Code</label>
                            <input type="text" class="form-control" id="color_code" name="color_code" placeholder="Color code" readonly>
                        </div>
                    </div>
                    <!-- END .card-body -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btn_submit" onclick="submit_form()">Add Color</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">

$(document).ready(function(){
    $('#color_name').on('keyup', function() {
        let color_name = $(this).val();
        if(color_name == "" || color_name == null || color_name == " "){
            $('#color_code').val("");
            return false;
        }
        let color_code = color_name.match(/\b(\w)/g).join('').toUpperCase();
        if(color_code.length > 2){
            color_code = color_code.substring(0,8);
        }
        let color_code_length = 8 - color_code.length;
        for (let index = 0; index < color_code_length; index++) {
            color_code += String.fromCharCode(Math.floor(Math.random() * 26) + 97).toUpperCase();
        }
        $('#color_code').val(color_code);
    });

    // ## Show Flash Message
    let session = {!! json_encode(session()->all()) !!};
    show_flash_message(session);

    $('#btn_modal_create').click((e) => {
        $('#modal_formLabel').text("Add Color")
        $('#btn_submit').text("Add Color")
        $('#color_form').attr('action', create_url);
        $('#color_form').find("input[type=text], textarea").val("");
        $('#color_form').find('input[name="_method"]').remove();
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
    $('#color_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('/color-data') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'color', name: 'color'},
            {data: 'color_code', name: 'color_code'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        lengthChange: true,
        searching: true,
        autoWidth: false,
        responsive: true,
    });


    // ## Form Validation
    let rules = {
        color: {
            required: true,
        },
        color_code: {
            required: true,
        },
    };
    let messages = {
        color: {
            required: "Please enter the color name",
        },
        color_code: {
            required: "Please enter color code",
        },
    };
    let validator = $("#color_form").validate({
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
    const create_url ='{{ route("color.store",":id") }}';
    const edit_url ='{{ route("color.show",":id") }}';
    const update_url ='{{ route("color.update",":id") }}';
    const delete_url ='{{ route("color.destroy",":id") }}';

    async function edit_color (color_id) {
        let url_edit = edit_url.replace(':id',color_id);

        result = await get_using_fetch(url_edit);
        form = $('#color_form')
        form.append('<input type="hidden" name="_method" value="PUT">');
        $('#modal_formLabel').text("Edit Color");
        $('#btn_submit').text("Save");
        $('#modal_form').modal('show')

        let url_update = update_url.replace(':id',color_id);
        form.attr('action', url_update);
        form.find('input[name="color"]').val(result.color);
        form.find('input[name="color_code"]').val(result.color_code);
    }

    async function delete_color (color_id) {
        data = { title: "Are you sure?" };
        let confirm_delete = await swal_delete_confirm(data);
        if(!confirm_delete) { return false; };

        let url_delete = delete_url.replace(':id',color_id);
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

    async function submit_form() {
        let form = $('#color_form');
        let url = form.attr('action');
        let data_params = form.serialize();
        let method = form.find('input[name="_method"]').val();

        if(method == "PUT"){
            result = await put_using_fetch(url, data_params);
        } else {
            result = await post_using_fetch(url, data_params);
        }

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




<script type="text/javascript">
</script>
@endpush