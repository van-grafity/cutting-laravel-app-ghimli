@extends('layouts.master')

@section('title', 'Cloth Roll')

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
                            onclick="modalClothRoll(true)"><i class="bx bx-plus font-size-16 align-middle me-2"></i>
                            Create
                        </a>
                    </div>
                    <table class="table align-middle table-nowrap table-hover table-datatable w-100">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" style="width: 70px;">#</th>
                                <th scope="col">PO Number</th>
                                <th scope="col">Fabric Type</th>
                                <th scope="col">Color</th>
                                <th scope="col">Roll Number</th>
                                <th scope="col">Width</th>
                                <th scope="col">Length</th>
                                <th scope="col">Weight</th>
                                <th scope="col">Batch Number</th>
                                <th scope="col" style="width: 100px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->po_id }}</td>
                                    <td>{{ $item->fabric_type }}</td>
                                    <td>{{ $item->color }}</td>
                                    <td>{{ $item->roll_no }}</td>
                                    <td>{{ $item->width }}</td>
                                    <td>{{ $item->length }}</td>
                                    <td>{{ $item->weight }}</td>
                                    <td>{{ $item->batch_no }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="javascript:void(0);" class="btn btn-primary btn-sm"
                                                onclick="modalClothRoll(false, {{ $item->id }})"><i
                                                    class="bx bx-edit-alt"></i></a>
                                            <form action="{{ url('/clothroll/' . $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm ms-1"><i
                                                        class="bx bx-trash"></i></button>
                                            </form>
                                        </div>
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
@endsection

@section('last-body')
    <div class="modal" tabindex="-1" role="dialog" id="modalClothRoll">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal Cloth Roll</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('/clothroll') }}" method="POST" class="custom-validation" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="po_id" class="form-label">PO Number</label>
                                    <input type="text" class="form-control" id="po_id" name="po_id" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fabric_type" class="form-label">Fabric Type</label>
                                    <input type="text" class="form-control" id="fabric_type" name="fabric_type" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="color" class="form-label">Color</label>
                                    <input type="text" class="form-control" id="color" name="color" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="roll_no" class="form-label">Roll Number</label>
                                    <input type="text" class="form-control" id="roll_no" name="roll_no" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="width" class="form-label">Width</label>
                                    <input type="text" class="form-control" id="width" name="width" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="length" class="form-label">Length</label>
                                    <input type="text" class="form-control" id="length" name="length" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="weight" class="form-label">Weight</label>
                                    <input type="text" class="form-control" id="weight" name="weight" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="batch_no" class="form-label">Batch Number</label>
                                    <input type="text" class="form-control" id="batch_no" name="batch_no" required>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection