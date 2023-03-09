@extends('layouts.master')

@section('title', 'Color')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table align-middle table-nowrap table-hover table-datatable" id="color_table">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" style="width: 70px;">#</th>
                                <th scope="col" class="text-left">Color</th>
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
                            <input type="text" class="form-control" id="color_code" name="color_code" placeholder="Enter code">
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
        $('#color_form').find("input[type=text], textarea").val("");
        $('#color_form').find('input[name="_method"]').remove();
        $('#modal_form').modal('show')
    })

    $(".btn-delete-color").on('click', function(e) {
        if(!confirm('apakah ingin menghapus data?')){
            return false;
        }
        let delete_url = $(this).attr('data-url');
        if (delete_url){
            delete_color_ajax(delete_url);
        } else {
            alert("not found!");
        }
    })

    $(".btn-edit-color").on('click', function(e) {
        let get_data_url = $(this).attr('data-url');
        if (get_data_url){
            get_data_color_ajax(get_data_url);
        } else {
            alert("not found!");
        }
    })

})
</script>

<script type="text/javascript">

    function get_data_color_ajax(get_data_url) {
        $.ajax({
            type:'GET',
            url:get_data_url,
            success:function(res){
                form = $('#color_form')
                form.append('<input type="hidden" name="_method" value="PUT">');
                $('#modal_formLabel').text("Edit Color");
                $('#btn_submit').text("Save");
                $('#modal_form').modal('show')

                form.attr('action', get_data_url);
                form.find('input[name="color"]').val(res.color);
                form.find('input[name="color_code"]').val(res.color_code);
            }
        }).catch((err)=>{
            console.log(err);
        });
    }

    function delete_color_ajax(delete_url) {
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

<script type="text/javascript">
    $(function (e) {
        $('#color_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('/color-data') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'color', name: 'color'},
                {data: 'color_code', name: 'color_code'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
    });
</script>
@endpush