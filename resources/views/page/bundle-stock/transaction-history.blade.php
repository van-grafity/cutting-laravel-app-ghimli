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
                    <div class="d-flex my-3">
                        {{-- <form class="my-auto ml-auto mr-2" action="{{ route("bundle-stock.sync-transaction") }}"  method="POST">
                            @csrf
                            @method("PUT")
                            <button type="submit" class="btn btn-light border btn-md">
                                Sync Data
                            </button>
                        </form> --}}
                        @can('admin-only')
                        <div class="ml-auto" style="width:200px;">
                            <select name="filter_type" id="filter_type" class="form-control no-search-box">
                                <option value="non_deleted" selected>Non-Deleted</option>
                                <option value="soft_deleted">Deleted</option>
                                <option value=0>All Data</option>
                            </select>
                        </div>
                        @endcan
                    </div>
                    <table class="table table-bordered table-hover text-center" id="transaction_history_table">
                        <thead class="">
                            <tr>
                                <th wihth="5%;">No.</th>
                                <th wihth="20%;">Serial Number</th>
                                <th wihth="10%;">GL Number</th>
                                <th wihth="10%;">Color</th>
                                <th wihth="10%;">Location</th>
                                <th wihth="5%;">Transaction Type</th>
                                <th wihth="10%;">Date</th>
                                <th wihth="5%;">Total Pcs</th>
                                <th wihth="5%;">Action</th>
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
            ajax:{
                    url: "{{ url('bundle-stock/dtable-bundle-transaction') }}",
                    data: function(d) {
                        d.filter_type = $('#filter_type').val() || 'non_deleted';
                    },
                },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'serial_number', name: 'serial_number'},
                {data: 'gl_number', name: 'gl_number'},
                {data: 'color', name: 'color'},
                {data: 'location', name: 'location'},
                {data: 'transaction_type', name: 'transaction_type'},
                {data: 'date', name: 'date'},
                {data: 'total_pcs', name: 'total_pcs'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            lengthChange: true,
            searching: true,
            autoWidth: false,
            responsive: true,
            drawCallback: function( settings ) {
                $('[data-toggle="tooltip"]').tooltip();
                Swal.close();
            }
        });
        $('#filter_type').change(function(event) {
            $('#transaction_history_table').DataTable().ajax.reload(null, false);
        });
    });

    function delete_bundle_stock_transaction(id, filterType){

        Swal.fire({
            title: 'Are you sure?',
            text: filterType !== "soft_deleted" ? "You only have 30 minutes to retrieve the ticket back after delete it!" : "Bundle stock transaction will be deleted permanently!" ,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: filterType !== "soft_deleted" ? "{{ url('/bundle-stock/transaction-history/soft-delete') }}"+'/'+id : "{{ url('/bundle-stock/transaction-history/delete') }}"+'/'+id ,
                    type: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id
                    },
                    success: function(data) {
                        if (data.status == 'success') {
                            Swal.fire(
                                'Deleted!',
                                data.message,
                                'success'
                        ).then(() => {
                            $('#transaction_history_table').DataTable().ajax.reload();
                        });
                        } else {
                            Swal.fire(
                                'Failed!',
                                data.message,
                                'error'
                            )
                        }
                    },
                    error: function(data) {
                        Swal.fire(
                            'Failed!',
                            'Something wrong',
                            'error'
                        )
                    }
                });
            }
        })
    }
</script>
@endpush
