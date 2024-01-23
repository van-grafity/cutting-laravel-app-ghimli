@extends('layouts.master')

@section('title', 'GL')

@section('content')
<style>
    .form-group {
        margin-bottom:0px;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-end mb-1">
                        <a href="javascript:void(0);" class="btn btn-success mb-2" id="btn_modal_create">Create</a>
                    </div>
                    <table class="table table-bordered table-hover" id="gl_table">
                        <thead class="">
                            <tr>
                                <th scope="col" class="text-left">No. </th>
                                <th scope="col" class="text-left">GL Number</th>
                                <th scope="col" class="text-left">Buyer</th>
                                <th scope="col" class="text-left">Season</th>
                                <th scope="col" class="text-left">Size Order</th>
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
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_formLabel">Add GL</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('gl.store') }}" method="POST" class="custom-validation" enctype="multipart/form-data" id="gl_form">
                @csrf
                <div class="modal-body">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="gl_number">GL Number</label>
                            <input type="text" class="form-control" id="gl_number" name="gl_number" placeholder="Enter GL Number">
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="gl_season">Season</label>
                                    <input type="text" class="form-control" id="gl_season" name="season" placeholder="Enter season">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="gl_size_order">Size Order</label>
                                    <input type="text" class="form-control" id="gl_size_order" name="size_order" placeholder="Enter Size Order">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="gl_buyer" class="form-label">Buyer</label>
                            <select name="buyer_id" class="form-control select2" id="gl_buyer" style="width: 100%;" data-placeholder="Choose Buyer">
                                <option value="">Choose Buyer</option>
                                @foreach($buyers as $key => $buyer)
                                    <option value="{{ $buyer->id }}" >{{ $buyer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        </br>

                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered table-hover" id="table_style">
                                    <thead>
                                        <tr>
                                            <th >Style</th>
                                            <th >Description</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <input type="hidden" name="style_ids[]" value="">
                                            <td width="150">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="style" name="style[]">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="style_desc" name="style_desc[]">
                                                </div>
                                            </td>
                                            <td width="100" class="text-center">
                                                <a href="javascript:void(0);" class="btn btn-success btn-sm p-2" onclick="add_new_tr()" ><i class="fas fa-plus"></i></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" name="combine_gl">
                            <label class="form-check-label" for="flexSwitchCheckDefault">Combine GL</label>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="gl_combine_name" class="form-label" style="display:none;">GL Combine Name</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="gl_combine_name" name="gl_combine_name" placeholder="Enter GL Combine Name" style="display:none;">
                                        <div class="input-group-append">
                                            <button class="btn btn-success" type="button" id="btn_add_gl_combine" style="display:none;">Add</button>
                                        </div>

                                        <div class="invalid-feedback"></div>
                                        <div class="valid-feedback"></div>
                                        <small class="text-muted">If you want to combine GL, please add GL Combine Name</small>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <table class="table table-bordered table-hover" id="table_res_gl_combine" style="display:none;">
                                    <thead>
                                        <tr>
                                            <th >GL Combine Name</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <!-- END .card-body -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btn_submit">Add GL</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
$(document).ready(function(){

    $('#flexSwitchCheckDefault').click(function(){
        if($(this).is(':checked')){
            $('#gl_combine_name').show();
            $('#btn_add_gl_combine').show();
            $('#table_res_gl_combine').show();
        } else {
            $('#gl_combine_name').hide();
            $('#btn_add_gl_combine').hide();
            $('#table_res_gl_combine').hide();
        }
    });
    
    $('#btn_add_gl_combine').click(function(){
        let gl_combine_name = $('#gl_combine_name').val();
        if(gl_combine_name == ''){
            swal_failed({
                title : "Please input GL Combine Name",
            });
            return false;
        }

        let element = `
        <tr>
            <td name="res_combine_name[]">${gl_combine_name}</td>
            <td class="text-center">
                <a href="javascript:void(0);" class="btn btn-danger btn-sm p-2 btn-style-delete"><i class="fas fa-trash-alt"></i></a>
            </td>
        </tr>
        `;
        $('#table_res_gl_combine > tbody').append(element);
        $('#gl_combine_name').val('');
    });

    $('#table_res_gl_combine > tbody').on("click",".btn-style-delete", function(e){ 
        e.preventDefault();
        $(this).parent().parent().remove();
    });
    
    // res_combine_name parsing to controller
    $('#gl_form').submit(function(e){
        e.preventDefault();
        let gl_combine_name = [];
        $('td[name="res_combine_name[]"]').each(function(){
            gl_combine_name.push($(this).text());
        });
        let gl_combine_name_json = JSON.stringify(gl_combine_name);
        $('#gl_form').append(`<input type="hidden" name="gl_combine_name_json" value='${gl_combine_name_json}'>`);
    });
    
    // ## Show Flash Message
    let session = {!! json_encode(session()->all()) !!};
    show_flash_message(session);

    $('.select2').select2({ 
        dropdownParent: $('#modal_form')
    });

    $('#btn_modal_create').click((e) => {
        $('#modal_formLabel').text("Add GL")
        $('#btn_submit').text("Add GL")
        $('#gl_form').attr("action", create_url);
        $('#gl_form').find('#gl_buyer').val('').trigger('change');
        $('#gl_form').find("input[type=text], textarea").val("");
        $('#gl_form').find('input[name="_method"]').remove();


        $('#table_style > tbody').html('');
        element_html = create_tr_element();
        $('#table_style > tbody').append(element_html);

        $('#modal_form').modal('show')
    })

    $('#modal_form').on('hidden.bs.modal', function () {
        $(this).find('.is-invalid').removeClass('is-invalid');
    });

    $("#gl_buyer").on("select2:close", function() {
        if ($(this).valid()) {
            $(this).removeClass("is-invalid");
            $(this).next(".invalid-feedback").remove();
        }
    });

    $('#table_style > tbody').on("click",".btn-style-delete", function(e){ 
        e.preventDefault();
        $(this).parent().parent().remove();
    });

})
</script>

<script type="text/javascript">
$(function (e) {

    // ## Datatable Initialize
    $('#gl_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('/gl-data') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'gl_number', name: 'gl_number'},
            {data: 'buyer', name: 'buyer'},
            {data: 'season', name: 'season'},
            {data: 'size_order', name: 'size_order'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        lengthChange: true,
        searching: true,
        autoWidth: false,
        responsive: true,
    });


    // ## Form Validation
    let rules = {
        gl_number: {
            required: true,
        },
        season: {
            required: true,
        },
        size_order: {
            required: true,
        },
        buyer_id: {
            required: true,
        },
        "style[]": {
            required: true,
        },
        "style_desc[]": {
            required: true,
        },
    };
    let messages = {
        gl_number: {
            required: "Please enter GL Number",
        },
        season: {
            required: "Please enter the season",
        },
        size_order: {
            required: "Please enter the size order",
        },
        buyer_id: {
            required: "Please select buyer",
        },
        "style[]": {
            required: "Please enter style name",
        },
        "style_desc[]": {
            required: "Please provide style description",
        },
    };
    let validator = $("#gl_form").validate({
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

    $('#gl_number').on('keyup', function(e){
        if($(this).val().length == 5){
            $(this).val($(this).val()+'-');
        }
        // if($(this).val().length > 8){
        //     $(this).val($(this).val().slice(0,8));
        // }
    });
});
</script>

<script type="text/javascript">
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const create_url ='{{ route("gl.store") }}';
    const edit_url ='{{ route("gl.show",":id") }}';
    const update_url ='{{ route("gl.update",":id") }}';
    const delete_url ='{{ route("gl.destroy",":id") }}';
    const fetch_style_url = '{{ route("fetch.style") }}';

    async function edit_gl(gl_id) {
        let url_edit = edit_url.replace(':id',gl_id);

        result = await get_using_fetch(url_edit);
        form = $('#gl_form')
        form.append('<input type="hidden" name="_method" value="PUT">');
        $('#modal_formLabel').text("Edit GL");
        $('#btn_submit').text("Save");

        let url_update = update_url.replace(':id',gl_id);
        form.attr('action', url_update);
        form.find('#gl_buyer').val(result.data.buyer_id).trigger('change');
        form.find('input[name="gl_number"]').val(result.data.gl_number);
        form.find('input[name="season"]').val(result.data.season);
        form.find('input[name="size_order"]').val(result.data.size_order);

        $('#table_res_gl_combine > tbody').html('');

        if(result.data.gl_combine.length > 0){
            $('#flexSwitchCheckDefault').prop('checked', true);
            $('#gl_combine_name').show();
            $('#btn_add_gl_combine').show();
            $('#table_res_gl_combine').show();
            result.data.gl_combine.forEach((data, i) => {
                let element = `
                <tr>
                    <td name="res_combine_name[]">${data.name}</td>
                    <td class="text-center">
                        <a href="javascript:void(0);" class="btn btn-danger btn-sm p-2 btn-style-delete"><i class="fas fa-trash-alt"></i></a>
                    </td>
                </tr>
                `;
                $('#table_res_gl_combine > tbody').append(element);
            });
        } else {
            $('#flexSwitchCheckDefault').prop('checked', false);
            $('#gl_combine_name').hide();
            $('#btn_add_gl_combine').hide();
            $('#table_res_gl_combine').hide();
        }

        // ## Get Style from the GL
        let data_style_params = { gl_id: gl_id };
        style_result = await using_fetch(fetch_style_url, data_style_params, "GET");
        data_style = style_result.data;

        // ## Insert to Style table list
        let button_type;
        data_style.forEach((data, i) => {
            if(i <= 0) {
                // ## If first row using button icon plus
                $('#table_style > tbody').html('');
                button_type = 'button-add';
            } else {
                button_type = 'button-delete';
            }

            let params = {
                data,
                button_type
            }
            element_html = create_tr_element(params);
            $('#table_style > tbody').append(element_html);
        });

        $('#modal_form').modal('show')
    }

    async function delete_gl(gl_id) {
        data = { title: "Are you sure?" };
        let confirm_delete = await swal_delete_confirm(data);
        if(!confirm_delete) { return false; };

        let url_delete = delete_url.replace(':id',gl_id);
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

    function add_new_tr(){
        element_html = create_tr_element({button_type: "button-delete"});
        $('#table_style > tbody').append(element_html);
    }

    function create_tr_element(params = {}) {
        // ## Create tr element with some option.
        data = params.hasOwnProperty('data') ? params.data : null;
        button_type = params.hasOwnProperty('button_type') ? params.button_type : 'button-add';
        
        let button_element;
        if(button_type == 'button-add') {
            button_element = `
            <a href="javascript:void(0);" class="btn btn-success btn-sm p-2" onclick="add_new_tr()"><i class="fas fa-plus"></i></a>
            `;
        } else {
            button_element = `
            <a href="javascript:void(0);" class="btn btn-danger btn-sm p-2 btn-style-delete"><i class="fas fa-trash-alt"></i></a>
            `;
        }

        let style_ids = data ? data.id : '';
        let style = data ? data.style : '';
        let style_desc = data ? data.description : '';
        let element = `
        <tr>
            <input type="hidden" name="style_id[]" value="${style_ids}">
            <td width="150">
                <div class="form-group">
                    <input type="text" class="form-control" name="style[]" value="${style}">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" class="form-control" name="style_desc[]" value="${style_desc}">
                </div>
            </td>
            <td width="100" class="text-center">
                ${button_element}
            </td>
        </tr>
        `
        return element;
    }

</script>
@endpush