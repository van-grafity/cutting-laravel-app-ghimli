@extends('layouts.master')

@section('title', 'Buyer')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="search-box me-2 mb-2 d-inline-block">
                            <div class="position-relative">
                                <input type="text" class="form-control searchTable" placeholder="Search">
                                <i class="bx bx-search-alt search-icon"></i>
                            </div>
                        </div>
                        <a href="javascript:void(0);" class="btn btn-success mb-2" id="btn_modal_create" data-id='2'>Create</a>
                    </div>
                    <table class="table align-middle table-nowrap table-hover">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="text-left">No. </th>
                                <th scope="col" class="text-left">Buyer's Name</th>
                                <th scope="col" class="text-left">Address</th>
                                <th scope="col" class="text-left">Shipment Address</th>
                                <th scope="col" class="text-left d-none">Code</th>
                                <th scope="col" class="text-left">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($buyers as $buyer)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $buyer->name }}</td>
                                <td>{{ $buyer->address }}</td>
                                <td>{{ $buyer->shipment_address }}</td>
                                <td class="d-none">{{ $buyer->code }}</td>
                                <td>
                                    <a href="javascript:void(0);" class="btn btn-primary btn-sm btn-edit-buyer" data-id="{{ $buyer->id }}" data-url="{{ route('buyer.show', $buyer->id) }}">Edit</a>
                                    <a href="javascript:void(0);" class="btn btn-danger btn-sm btn-delete-buyer" data-id="{{ $buyer->id }}" data-url="{{ route('buyer.destroy', $buyer->id) }}">Delete</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
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
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#btn_modal_create').click((e) => {
        $('#modal_formLabel').text("Add Buyer")
        $('#btn_submit').text("Add Buyer")
        $('#buyer_form').find("input[type=text], textarea").val("");
        $('#buyer_form').find('input[name="_method"]').remove();
        $('#modal_form').modal('show')
    })

    $(".btn-delete-buyer").on('click', function(e) {
        if(!confirm('apakah ingin menghapus data?')){
            return false;
        }
        let delete_url = $(this).attr('data-url');
        if (delete_url){
            delete_buyer_ajax(delete_url);
        } else {
            alert("not found!");
        }
    })

    $(".btn-edit-buyer").on('click', function(e) {
        let get_data_url = $(this).attr('data-url');
        if (get_data_url){
            get_data_buyer_ajax(get_data_url);
        } else {
            alert("not found!");
        }
    })

})
</script>

<script type="text/javascript">
    function delete_buyer_ajax(delete_url) {
        $.ajax({
            type:'DELETE',
            url:delete_url,
            success:function(res){
                console.log(res);
                if($.isEmptyObject(res.error)){
                    alert(res.status);
                    location.reload();
                } else {
                    console.log("lah error");
                }
            }
        }).catch((err)=>{
            console.log(err);
        });
    }

    function get_data_buyer_ajax(get_data_url) {
        $.ajax({
            type:'GET',
            url:get_data_url,
            success:function(res){
                form = $('#buyer_form')
                form.append('<input type="hidden" name="_method" value="PUT">');
                $('#modal_formLabel').text("Edit Buyer");
                $('#btn_submit').text("Save");
                $('#modal_form').modal('show')

                form.attr('action', get_data_url);
                form.find('input[name="name"]').val(res.name);
                form.find('input[name="address"]').val(res.address);
                form.find('input[name="shipment_address"]').val(res.shipment_address);
                form.find('input[name="code"]').val(res.code);
            }
        }).catch((err)=>{
            console.log(err);
        });
    }

    
</script>
@endpush