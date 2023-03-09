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
                            <tr>
                                <td>01</td>
                                <td>62843-001</td>
                                <td>62843-00</td>
                                <td>MED HEATHER GREY H125R (053)</td>
                                <td>01</td>
                                <td>
                                    <a href="" class="btn btn-primary btn-sm btn-edit-layingPlanning">Edit</a>
                                    <a href="javascript:void(0);" class="btn btn-danger btn-sm btn-delete-layingPlanning">Delete</a>
                                    <a href="{{ route('cutting-order.show', 1) }}" class="btn btn-info btn-sm">Detail</a>
                                </td>
                            </tr>
                            <tr>
                                <td>02</td>
                                <td>62843-002</td>
                                <td>62843-00</td>
                                <td>MED HEATHER GREY H125R (053)</td>
                                <td>02</td>
                                <td>
                                    <a href="" class="btn btn-primary btn-sm btn-edit-layingPlanning">Edit</a>
                                    <a href="javascript:void(0);" class="btn btn-danger btn-sm btn-delete-layingPlanning">Delete</a>
                                    <a href="{{ route('cutting-order.show', 1) }}" class="btn btn-info btn-sm">Detail</a>
                                </td>
                            </tr>
                            <tr>
                                <td>03</td>
                                <td>62843-003</td>
                                <td>62843-00</td>
                                <td>MED HEATHER GREY H125R (053)</td>
                                <td>03</td>
                                <td>
                                    <a href="" class="btn btn-primary btn-sm btn-edit-layingPlanning">Edit</a>
                                    <a href="javascript:void(0);" class="btn btn-danger btn-sm btn-delete-layingPlanning">Delete</a>
                                    <a href="{{ route('cutting-order.show', 1) }}" class="btn btn-info btn-sm">Detail</a>
                                </td>
                            </tr>
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

</script>
@endpush