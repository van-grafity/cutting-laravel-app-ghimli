@extends('layouts.master')

@section('title', 'Buyer')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-end mb-1">
                        <a href="javascript:void(0);" class="btn btn-success mb-2" id="btn_modal_create" data-id='2'>Create</a>
                    </div>
                    <table class="table table-bordered table-hover" id="buyer_table">
                        <thead class="">
                            <tr>
                                <th scope="col" style="width: 20px">No</th>
                                <th scope="col" class="text-left">Name</th>
                                <th scope="col" class="text-left">Address</th>
                                <th scope="col" class="text-left">Shipment Address</th>
                                <th scope="col" class="text-left">Code</th>
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
                <h5 class="modal-title" id="modal_formLabel">Add Buyer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('buyer.store') }}" method="POST" class="custom-validation" enctype="multipart/form-data" id="buyer_form">
                @csrf
                <div class="modal-body">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="buyer_name">Name</label>
                            <input type="text" class="form-control" id="buyer_name" name="name" placeholder="Enter name">
                        </div>
                        <div class="form-group">
                            <label for="buyer_address">Address</label>
                            <input type="text" class="form-control" id="buyer_address" name="address" placeholder="Enter address">
                        </div>
                        <div class="form-group">
                            <label for="shipment_address">Shipement Address</label>
                            <input type="text" class="form-control" id="shipment_address" name="shipment_address" placeholder="Enter address">
                        </div>
                        <div class="form-group">
                            <label for="buyer_code">Code</label>
                            <input type="text" class="form-control" id="buyer_code" name="code" placeholder="Enter code">
                        </div>
                    </div>
                    <!-- END .card-body -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btn_submit">Add Buyer</button>
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
        $('#modal_formLabel').text("Add Buyer")
        $('#btn_submit').text("Add Buyer")
        $('#buyer_form').attr('action', create_url);
        $('#buyer_form').find("input[type=text], textarea").val("");
        $('#buyer_form').find('input[name="_method"]').remove();
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
    $('#buyer_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('/buyer-data') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name', name: 'name'},
            {data: 'address', name: 'address'},
            {data: 'shipment_address', name: 'shipment_address'},
            {data: 'code', name: 'code'},
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
        address: {
            required: true,
        },
        shipment_address: {
            required: true,
        },
        code: {
            required: true,
        }
    };
    let messages = {
        name: {
            required: "Please enter the buyer's name",
        },
        address: {
            required: "Please enter address",
        },
        shipment_address: {
            required: "Please enter shipment address",
        },
        code: {
            required: "Please enter buyer code",
        },
    };
    let validator = $("#buyer_form").validate({
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
    const create_url ='{{ route("buyer.store",":id") }}';
    const edit_url ='{{ route("buyer.show",":id") }}';
    const update_url ='{{ route("buyer.update",":id") }}';
    const delete_url ='{{ route("buyer.destroy",":id") }}';


    async function edit_buyer(buyer_id) {
        let url_edit = edit_url.replace(':id',buyer_id);

        result = await get_using_fetch(url_edit);
        form = $('#buyer_form')
        form.append('<input type="hidden" name="_method" value="PUT">');
        $('#modal_formLabel').text("Edit Buyer");
        $('#btn_submit').text("Save");
        $('#modal_form').modal('show')

        let url_update = update_url.replace(':id',buyer_id);
        form.attr('action', url_update);
        form.find('input[name="name"]').val(result.name);
        form.find('input[name="address"]').val(result.address);
        form.find('input[name="shipment_address"]').val(result.shipment_address);
        form.find('input[name="code"]').val(result.code);
    }

    async function delete_buyer(buyer_id) {
        data = { title: "Are you sure?" };
        let confirm_delete = await swal_delete_confirm(data);
        if(!confirm_delete) { return false; };

        let url_delete = delete_url.replace(':id',buyer_id);
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