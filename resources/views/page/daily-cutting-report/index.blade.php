@extends('layouts.master')

@section('title', 'Daily Cutting Report')

@section('content')

<div class="container">
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

@endsection

@push('js')
<script type="text/javascript">

$(document).ready(function(){

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


});
</script>

@endpush