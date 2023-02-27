@extends('layouts.master')

@section('title', 'Buyer')

@section('content')

<div class="container">
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
                        <a href="javascript:void(0);" class="btn btn-success btn-rounded waves-effect waves-light mb-2" onclick="">Create</a>
                    </div>
                    <table class="table align-middle table-nowrap table-hover">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">No. </th>
                                <th scope="col">Buyer's Name</th>
                                <th scope="col">Address</th>
                                <th scope="col">Code</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Aeropostale</td>
                                <td>Cempaka Putih no. 25</td>
                                <td>AERO-02</td>
                                <td>EDIT - DELETE</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection