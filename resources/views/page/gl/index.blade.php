@extends('layouts.master')

@section('title', 'GL')

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
                        <a href="javascript:void(0);" class="btn btn-success mb-2" id="btn_modal_create">Create</a>
                    </div>
                    <table class="table align-middle table-nowrap table-hover">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="text-left">No. </th>
                                <th scope="col" class="text-left">GL Number</th>
                                <th scope="col" class="text-left">Season</th>
                                <th scope="col" class="text-left">Size Order</th>
                                <th scope="col" class="text-left">Gl</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($gls as $gl)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $gl->gl_number }}</td>
                                <td>{{ $gl->season }}</td>
                                <td>{{ $gl->size_order }}</td>
                                <td>{{ $gl->buyer->name }}</td>
                                <td>
                                    <a href="javascript:void(0);" class="btn btn-primary btn-sm btn-edit-gl" data-id="{{ $gl->id }}" data-url="{{ route('gl.show', $gl->id) }}">Edit</a>
                                    <a href="javascript:void(0);" class="btn btn-danger btn-sm btn-delete-gl" data-id="{{ $gl->id }}" data-url="{{ route('gl.destroy', $gl->id) }}">Delete</a>
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
                        <div class="form-group">
                            <label for="gl_season">Season</label>
                            <input type="text" class="form-control" id="gl_season" name="season" placeholder="Enter season">
                        </div>
                        <div class="form-group">
                            <label for="gl_size_order">Size Order</label>
                            <input type="text" class="form-control" id="gl_size_order" name="size_order" placeholder="Enter Size Order">
                        </div>
                        <div class="form-group">
                            <label for="gl_buyer">Buyer</label>
                            <select name="buyer_id" class="form-control" id="gl_buyer">
                                <option value="">Choose Buyer</option>
                                @foreach($buyers as $key => $buyer)
                                    <option value="{{ $buyer->id }}" >{{ $buyer->name }}</option>
                                @endforeach
                            </select>
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
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#btn_modal_create').click((e) => {
        $('#modal_formLabel').text("Add GL")
        $('#btn_submit').text("Add GL")
        $('#gl_form').attr("action", create_url);
        $('#gl_form').find("input[type=text], textarea").val("");
        $('#gl_form').find('input[name="_method"]').remove();
        $('#modal_form').modal('show')
    })

    $(".btn-delete-gl").on('click', function(e) {
        if(!confirm('apakah ingin menghapus data?')){
            return false;
        }
        let delete_url = $(this).attr('data-url');
        if (delete_url){
            delete_gl_ajax(delete_url);
        } else {
            alert("not found!");
        }
    })

    $(".btn-edit-gl").on('click', function(e) {
        let get_data_url = $(this).attr('data-url');
        if (get_data_url){
            get_data_gl_ajax(get_data_url);
        } else {
            alert("not found!");
        }
    })

})
</script>

<script type="text/javascript">
    const create_url ='{{ route("gl.store",":id") }}';
    
    function delete_gl_ajax(delete_url) {
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

    function get_data_gl_ajax(get_data_url) {
        $.ajax({
            type:'GET',
            url:get_data_url,
            success:function(res){
                form = $('#gl_form')
                form.append('<input type="hidden" name="_method" value="PUT">');
                $('#modal_formLabel').text("Edit Gl");
                $('#btn_submit').text("Save");
                $('#modal_form').modal('show')

                form.attr('action', get_data_url);
                form.find('input[name="gl_number"]').val(res.gl_number);
                form.find('input[name="season"]').val(res.season);
                form.find('input[name="size_order"]').val(res.size_order);

                $('#gl_buyer').val(res.buyer_id)

            }
        }).catch((err)=>{
            console.log(err);
        });
    }

    
</script>
@endpush