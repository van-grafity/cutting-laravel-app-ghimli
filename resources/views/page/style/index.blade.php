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
})
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
        if(!confirm("Apakah anda yakin ingin menghapus Style ini?")) { return false; };

        let url_delete = delete_url.replace(':id',style_id);
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
            // dom: 'Bfrtip',
            // dom: '<"wrapperx"flipt>',
            // dom: '<"top"i>rt<"bottom"flp><"clear">',
            // dom: '<"top"i>rt<"bottom"flp><"clear">',
            // dom:    "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-3'l><'col-sm-12 col-md-3'f>>" +
            //         "<'row'<'col-sm-12'tr>>" +
            //         "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            // buttons: [ 'copy', 'csv', 'excel', 'pdf', 'print'],
            // paging: true,
            lengthChange: true,
            searching: true,
            // ordering: true,
            // info: true,
            autoWidth: false,
            responsive: true,
        });
        // }).buttons().container().appendTo('#style_table_wrapper .col-md-6:eq(0)');

    });
    
</script>
@endpush