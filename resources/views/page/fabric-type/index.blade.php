@extends('layouts.master')

@section('title', 'Fabric Type')

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
                                <th scope="col" class="text-left">Name</th>
                                <th scope="col" class="text-left">Description</th>
                                <th scope="col" class="text-left">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($fabricTypes as $fabricType)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $fabricType->name }}</td>
                                <td>{{ $fabricType->description }}</td>
                                <td>
                                    <a href="javascript:void(0);" class="btn btn-primary btn-sm btn-edit-fabricType" data-id="{{ $fabricType->id }}" data-url="{{ route('fabric-type.show', $fabricType->id) }}">Edit</a>
                                    <a href="javascript:void(0);" class="btn btn-danger btn-sm btn-delete-fabricType" data-id="{{ $fabricType->id }}" data-url="{{ route('fabric-type.destroy', $fabricType->id) }}">Delete</a>
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
                            <input type="text" class="form-control" id="description" name="description" placeholder="Enter description">
                        </div>
                    </div>
                    <!-- END .card-body -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btn_submit">Add Color</button>
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
        $('#modal_formLabel').text("Add Color")
        $('#btn_submit').text("Add Color")
        $('#fabricType_form').find("input[type=text], textarea").val("");
        $('#fabricType_form').find('input[name="_method"]').remove();
        $('#modal_form').modal('show')
    })

    $(".btn-delete-fabricType").on('click', function(e) {
        if(!confirm('apakah ingin menghapus data?')){
            return false;
        }
        let delete_url = $(this).attr('data-url');
        if (delete_url){
            delete_fabricType_ajax(delete_url);
        } else {
            alert("not found!");
        }
    })

    $(".btn-edit-fabricType").on('click', function(e) {
        let get_data_url = $(this).attr('data-url');
        if (get_data_url){
            get_data_fabricType_ajax(get_data_url);
        } else {
            alert("not found!");
        }
    })

})
</script>

<script type="text/javascript">

    function get_data_fabricType_ajax(get_data_url) {
        $.ajax({
            type:'GET',
            url:get_data_url,
            success:function(res){
                form = $('#fabricType_form')
                form.append('<input type="hidden" name="_method" value="PUT">');
                $('#modal_formLabel').text("Edit Fabric Type");
                $('#btn_submit').text("Save");
                $('#modal_form').modal('show')

                form.attr('action', get_data_url);
                form.find('input[name="name"]').val(res.name);
                form.find('input[name="description"]').val(res.description);
            }
        }).catch((err)=>{
            console.log(err);
        });
    }

    function delete_fabricType_ajax(delete_url) {
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

    

    
</script>
@endpush