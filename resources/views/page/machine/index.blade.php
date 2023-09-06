@extends('layouts.master')
@section('title', 'Machine')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-striped table-bordered" id="machine-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Machine Type</th>
                                <th>Brand</th>
                                <th>Model</th>
                                <th>Serial Number</th>
                            </tr>
                        </thead>
                        <tbody>
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
$(function (e) {
    $('#machine-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('machine-data') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'machine_type', name: 'machine_type'},
            {data: 'brand', name: 'brand'},
            {data: 'model', name: 'model'},
            {data: 'serial_number', name: 'serial_number'},
        ],
        lengthChange: true,
        searching: true,
        autoWidth: false,
        responsive: true,
    });
});
</script>
@endpush