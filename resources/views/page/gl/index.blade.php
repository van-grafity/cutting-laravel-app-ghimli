@extends('layouts.master')

@section('title', 'GL')

@section('content')

<div class="container">
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
                            <label for="gl_buyer" class="form-label">Buyer</label>
                            <select name="buyer_id" class="form-control select2" id="gl_buyer" style="width: 100%;" data-placeholder="Choose Buyer">
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
        $('#modal_form').modal('show')
    })

})
</script>

<script type="text/javascript">
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const create_url ='{{ route("gl.store",":id") }}';
    const edit_url ='{{ route("gl.show",":id") }}';
    const update_url ='{{ route("gl.update",":id") }}';
    const delete_url ='{{ route("gl.destroy",":id") }}';
    

    async function edit_gl(user_id) {
        let url_edit = edit_url.replace(':id',user_id);

        result = await get_using_fetch(url_edit);
        form = $('#gl_form')
        form.append('<input type="hidden" name="_method" value="PUT">');
        $('#modal_formLabel').text("Edit GL");
        $('#btn_submit').text("Save");
        $('#modal_form').modal('show')

        let url_update = update_url.replace(':id',user_id);
        form.attr('action', url_update);
        form.find('#gl_buyer').val(result.buyer_id).trigger('change');
        form.find('input[name="gl_number"]').val(result.gl_number);
        form.find('input[name="season"]').val(result.season);
        form.find('input[name="size_order"]').val(result.size_order);
    }

    async function delete_gl(user_id) {
        if(!confirm("Apakah anda yakin ingin menghapus GL ini?")) { return false; };

        let url_delete = delete_url.replace(':id',user_id);
        let data_params = { token };

        result = await delete_using_fetch(url_delete, data_params)
        if(result.status == "success"){
            alert(result.message)
            location.reload();
        } else {
            console.log(result.message);
            alert("Terjadi Kesalahan");
        }
    }
</script>

<script type="text/javascript">
    $(function (e) {
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
    });
</script>
@endpush