@extends('layouts.master')

@section('title', 'Purchase Order')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="search-box me-2 mb-2 d-inline-block">
                            <div class="position-relative">
                                <input type="text" class="form-control searchTable"
                                    placeholder="Search">
                                <i class="bx bx-search-alt search-icon"></i>
                            </div>
                        </div>
                        <a href="javascript:void(0);" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2"
                            onclick="modalPurchaseOrder(true)"><i class="bx bx-plus font-size-16 align-middle me-2"></i>
                            Create
                        </a>
                    </div>
                    <table class="table align-middle table-nowrap table-hover table-datatable w-100">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" style="width: 70px;">#</th>
                                <th scope="col">PO Number</th>
                                <th scope="col">Vendor</th>
                                <th scope="col">Company Name</th>
                                <th scope="col">Product</th>
                                <th scope="col">Order Date</th>
                                <th scope="col" style="width: 100px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $dataPurchaseOrder)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $dataPurchaseOrder->po_no }}</td>
                                    <td>{{ $dataPurchaseOrder->vendor_id }}</td>
                                    <td>{{ $dataPurchaseOrder->company_name }}</td>
                                    <td>{{ $dataPurchaseOrder->product_id }}</td>
                                    <td>{{ $dataPurchaseOrder->order_date }}</td>
                                    <td>
                                    <a href="javascript:void(0);" class="btn btn-primary btn-sm"
                                            onclick="modalPurchaseOrder(false, {{ $dataPurchaseOrder->id }})"><i
                                                class="bx bx-edit-alt"></i></a>
                                    <form action="/purchaseorder/{{ $dataPurchaseOrder->id }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="bx bx-trash"></i></button>
                                    </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->

    <div class="modal" tabindex="-1" role="dialog" id="modalPurchaseOrder">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal Purchase Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('/purchaseorder') }}" method="POST" class="custom-validation" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="mb-3">
                            <label for="po_number" class="form-label">PO Number</label>
                            <input type="text" class="form-control" id="po_number" name="po_no" required>
                        </div>
                        <div class="mb-3">
                            <label for="vendor_id" class="form-label">Vendor</label>
                            <input type="text" class="form-control" id="vendor_id" name="vendor_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="vendor" class="form-label">Company Name</label>
                            <input type="text" class="form-control" id="company_name" name="company_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="vendor" class="form-label">Product</label>
                            <input type="text" class="form-control" id="product_id" name="product_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="vendor" class="form-label">Order Date</label>
                            <input type="date" class="form-control" id="order_date" name="order_date" required>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection