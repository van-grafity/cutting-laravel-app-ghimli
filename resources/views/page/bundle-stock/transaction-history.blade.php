@extends('layouts.master')

@section('title', 'Transaction History')

@section('content')
@if(session()->has('success'))
<script>
    toastr.success("{{ session()->get('success') }}");
</script>
@endif
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="content-title text-center">
                        <h3>Transaction History</h3>
                    </div>
                    <form action="{{ route("bundle-stock.sync-transaction") }}" class="d-flex" method="POST">
                        @csrf
                        @method("PUT")
                        <button type="submit" class="btn btn-light border btn-md ml-auto my-2">
                            Sync Data
                        </button>
                    </form>
                    <table class="table table-bordered table-hover text-center" id="transaction_history_table">
                        <thead class="">
                            <tr>
                                <th wihth="5%;">No.</th>
                                <th wihth="20%;">Serial Number</th>
                                <th wihth="10%;">GL Number</th>
                                <th wihth="10%;">Color</th>
                                <th wihth="10%;">Location</th>
                                <th wihth="10%;">Date</th>
                                <th wihth="5%;">Total Pcs</th>
                            </tr>
                        </thead>
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
        $('#transaction_history_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('bundle-transfer-note/dtable') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'serial_number', name: 'serial_number'},
                {data: 'gl_number', name: 'gl_number'},
                {data: 'color', name: 'color'},
                {data: 'location', name: 'location'},
                {data: 'date', name: 'date'},
                {data: 'total_pcs', name: 'total_pcs'},
            ],
        });
    });
</script>
@endpush
