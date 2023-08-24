@extends('layouts.master')

@section('title', 'User')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-end mb-1">
                        <a href="javascript:void(0);" class="btn btn-success mb-2" id="btn_modal_create" onclick="showModalCuttingGroup(true)">Create Group</a>
                    </div>
                    <table class="table table-bordered table-hover" id="user_table">
                        <thead class="">
                            <tr>
                                <th scope="col" style="width: 20px">No</th>
                                <th scope="col" style="width: 20%;">Group Name</th>
                                <th scope="col" style="width: 20%;">Group Description</th>
                                <th scope="col" style="width: 20%;"class="text-left">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="modal_formLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">    
        <div class="modal-content">
            <form action="{{ route('store-group') }}" method="POST" id="form_modal" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="form-group">
                    <label for="group_name">Group Name</label>
                    <input type="text" class="form-control" id="group_name" name="group_name" placeholder="Enter Group Name">
                </div>
                <div class="form-group">
                    <label for="group_description">Group Description</label>
                    <input type="text" class="form-control" id="group_description" name="group_description" placeholder="Enter Group Description">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="btn_close_modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn_submit_modal">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
$(function (e) {

    $('#user_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('/group-data') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'group_name', name: 'group_name'},
            {data: 'group_description', name: 'group_description'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        lengthChange: true,
        searching: true,
        autoWidth: false,
        responsive: true,
    });

    let rules = {
        group_name: {
            required: true,
        },
        group_description: {
            required: true,
        },
    };
    let messages = {
        group_name: {
            required: "Please enter the group name",
        },
        group_description: {
            required: "Please enter the group description",
        },
    };
});

function showModalCuttingGroup(add, id = null) {
    var modal = $('#modal_form'),
        form = $('#form_modal');
    if (add) {
        $('#modal_form').modal('show');
        $('#modal_formLabel').text('Create Group');
        $('#group_name').val('');
        $('#group_description').val('');
        $('#form_modal').attr('action', "{{ route('store-group') }}");
    } else {
        // form.trigger('reset').parsley().reset();
        form.attr('action', "{{ route('update-group', ':id') }}".replace(':id', id));
        form.find('[name="_method"]').val('PUT');
        $('#modal_formLabel').text('Edit Group');
        $.ajax({
            url: "{{ url('/edit-group') }}/" + id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                $('#modal_form').modal('show');
                $('#modal_formLabel').text('Edit Group');
                $('#group_name').val(data.group_name);
                $('#group_description').val(data.group_description);
                $('#btn_submit_modal').show();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }
}

function confirmDelete(self) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ url('/delete-group') }}/" + id,
                type: "GET",
                dataType: "JSON",
                success: function (data) {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'Ok',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }
    });
}
</script>

<script type="text/javascript">

$(document).ready(function(){
});
    

</script>
@endpush