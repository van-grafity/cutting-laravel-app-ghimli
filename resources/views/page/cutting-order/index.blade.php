@extends('layouts.master')

@section('title', 'Cutting Order Record')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="content-title text-center">
                        <h3>Cutting Order Record List</h3>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="search-box me-2 mb-2 d-inline-block">
                            <div class="position-relative">
                                <input type="text" class="form-control searchTable" placeholder="Search">
                                <i class="bx bx-search-alt search-icon"></i>
                            </div>
                        </div>
                        <a href="javascript:void(0);" class="btn btn-success mb-2 d-none" id="btn_modal_create">Create</a>
                    </div>

                    <table class="table align-middle table-nowrap table-hover">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="">No. </th>
                                <th scope="col" class="">No Laying Sheet</th>
                                <th scope="col" class="">GL</th>
                                <th scope="col" class="">Color</th>
                                <th scope="col" class="">Table No</th>
                                <th scope="col" class="">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $data as $cuttingOrderRecord )
                            <tr>
                                <td>{{ $cuttingOrderRecord->no }}</td>
                                <td>{{ $cuttingOrderRecord->no_laying_sheet }}</td>
                                <td>{{ $cuttingOrderRecord->gl_number }}</td>
                                <td>{{ $cuttingOrderRecord->color }}</td>
                                <td>{{ $cuttingOrderRecord->table_number}}</td>
                                <td>
                                    <a href="javascript:void(0);" class="btn btn-primary btn-sm btn-cor-print">Print</a>
                                    <a href="javascript:void(0);" class="btn btn-danger btn-sm btn-cor-delete" data-id="{{ $cuttingOrderRecord->id}}">Delete</a>
                                    <a href="{{ route('cutting-order.show', $cuttingOrderRecord->id) }}" class="btn btn-info btn-sm">Detail</a>
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
$(document).ready(function() {

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const delete_url ='{{ route("cutting-order.destroy",":id") }}';

    $('.btn-cor-delete').on('click', async function(e){
        if(!confirm("Apakah anda yakin ingin menghapus Cutting Order Record ini?")) { return false; };

        let cutting_order_id = $(this).data('id');
        let url = delete_url.replace(':id',cutting_order_id);
        let data_params = { token };

        result = await delete_using_fetch(url, data_params);
        if(result.status == "success"){
            alert(result.message)
            laying_planning_detail_id = $(this).attr('data-id');
            location.reload();
        } else {
            console.log(result.message);
            alert("Terjadi Kesalahan");
        }
    });
});
</script>
@endpush