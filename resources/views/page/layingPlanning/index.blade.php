@extends('layouts.master')

@section('title', 'Laying Planning')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="content-title text-center">
                        <h3>Laying Planning List</h3>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="search-box me-2 mb-2 d-inline-block">
                            <div class="position-relative">
                                <input type="text" class="form-control searchTable" placeholder="Search">
                                <i class="bx bx-search-alt search-icon"></i>
                            </div>
                        </div>
                        <a  href="{{ url('/laying-planning-create') }}" class="btn btn-success mb-2">Create</a>
                    </div>
                    <table class="table align-middle table-nowrap table-hover">
                        <thead class="table-light">
                            <tr>
                            <th scope="col">No. </th>
                                <th scope="col" width="100">Gl No.</th>
                                <th scope="col">Style</th>
                                <th scope="col">Buyer</th>
                                <th scope="col">Color</th>
                                <th scope="col">Fabric Type</th>
                                <th scope="col" width="150">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $layingPlanning)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $layingPlanning->gl->gl_number }}</td>
                                <td>{{ $layingPlanning->style->style }}</td>
                                <td>{{ $layingPlanning->buyer->name }}</td>
                                <td>{{ $layingPlanning->color->color }}</td>
                                <td>{{ $layingPlanning->fabricType->description }}</td>
                                <td>
                                    <a href="{{ route('laying-planning.edit',$layingPlanning->id) }}" class="btn btn-primary btn-sm btn-edit-layingPlanning" data-id="{{ $layingPlanning->id }}">Edit</a>
                                    <a href="javascript:void(0);" class="btn btn-danger btn-sm btn-delete-layingPlanning" data-id="{{ $layingPlanning->id }}" data-url="{{ route('laying-planning.destroy', $layingPlanning->id) }}">Delete</a>
                                    <a href="{{ route('laying-planning.show',$layingPlanning->id) }}" class="btn btn-info btn-sm mt-1" data-id="{{ $layingPlanning->id }}" data-url="">Detail</a>
                                    <a  href="{{ url('/laying-planning-qrcode/'.$layingPlanning->id) }}" class="btn btn-primary btn-sm">QR Code</a>
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
<div class="modal fade" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="modal_create_form" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_create_form">Add Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('buyer.store') }}" method="POST" class="custom-validation" enctype="multipart/form-data" id="create_form">
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
                            <label for="buyer_code">Code</label>
                            <input type="text" class="form-control" id="buyer_code" name="code" placeholder="Enter code">
                        </div>
                    </div>
                    <!-- END .card-body -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btn_submit">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')

<script type="text/javascript">
$(document).ready(function(){
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#btn_modal_create').click((e) => {
        reset_form({title: "Add Planning", btn_textx: "Add Plan"});
    })

    $('.btn-delete-layingPlanning').on('click', async function(e){

        if(!confirm("Apakah anda yakin ingin menghapus laying planning ini?")) {
            return false;
        }
        let laying_planning_id = $(this).attr('data-id');
        let url_delete = $(this).attr('data-url');
        const data_params = {
            token: token
        };

        result = await delete_using_fetch(url_delete, data_params);
        if(result.status == "success"){
            alert(result.message)
            laying_planning_id = $(this).attr('data-id');
            location.reload();
        } else {
            alert("Terjadi Kesalahan");
        }

    })


})
</script>

<script type="text/javascript">
    function reset_form(data = {}) {
        $('#modal_create_form').text(data.title);
        $('#btn_submit').text(data.btn_text);
        $('#create_form').find("input[type=text], textarea").val("");
        $('#create_form').find('input[name="_method"]').remove();
        $('#modal_form').modal('show')
    }
</script>

<script type="text/javascript">
    async function delete_using_fetch(url = "", data = {}) {
    // Default options are marked with *
        const response = await fetch(url, {
            method: "DELETE",
            mode: "cors",
            cache: "no-cache",
            credentials: "same-origin",
            headers: {
                "Content-Type": "application/json",
                'X-CSRF-TOKEN': data.token
            },
            redirect: "follow",
            referrerPolicy: "no-referrer",
        });
        return response.json();
    }

</script>
@endpush('js')