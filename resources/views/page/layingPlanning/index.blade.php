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
                                <th scope="col" width="150">Serial Number</th>
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
                                <td>{{ $layingPlanning->serial_number }}</td>
                                <td>{{ $layingPlanning->style->style }}</td>
                                <td>{{ $layingPlanning->buyer->name }}</td>
                                <td>{{ $layingPlanning->color->color }}</td>
                                <td>{{ $layingPlanning->fabricType->description }}</td>
                                <td>
                                    <a href="{{ route('laying-planning.edit',$layingPlanning->id) }}" class="btn btn-primary btn-sm btn-edit-layingPlanning" data-id="{{ $layingPlanning->id }}">Edit</a>
                                    <a href="javascript:void(0);" class="btn btn-danger btn-sm btn-planning-delete" data-id="{{ $layingPlanning->id }}" data-url="{{ route('laying-planning.destroy', $layingPlanning->id) }}">Delete</a>
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

@endsection

@push('js')
<script type="text/javascript">
$(document).ready(function(){
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    $('.btn-planning-delete').on('click', async function(e){
        if(!confirm("Apakah anda yakin ingin menghapus laying planning ini?")) {
            return false;
        }
        let laying_planning_id = $(this).attr('data-id');
        let url_delete = $(this).attr('data-url');
        let data_params = { token };

        result = await delete_using_fetch(url_delete, data_params);
        if(result.status == "success"){
            alert(result.message)
            laying_planning_id = $(this).attr('data-id');
            location.reload();
        } else {
            alert("Terjadi Kesalahan");
        }
    });
})
</script>

@endpush('js')