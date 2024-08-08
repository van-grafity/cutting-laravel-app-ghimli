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
                        <div class="ml-auto d-flex flex-row" style="gap: 0.5rem">
                            <select name="transaction_type" id="transaction_type" class="form-control" style="min-width: 150px;">
                                <option value=0 selected>All Transaction</option>
                                <option value="IN">IN</option>
                                <option value="OUT">OUT</option>
                            </select>
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
                                <th width="5%;">No.</th>
                                <th width="15%;">Serial Number</th>
                                <th width="10%;">GL Number</th>
                                <th width="10%;">No. Table</th>
                                <th width="10%;">Color</th>
                                <th width="10%;">Location</th>
                                <th width="1%;">Transaction Type</th>
                                <th width="10%;">Date</th>
                                <th width="5%;">Total Pcs</th>
                                <th width="5%;">Action</th>
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

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    // ## Show Flash Message
    let session = {!! json_encode(session()->all()) !!};
    show_flash_message(session);

    $(function (e) {
        $('#transaction_history_table').DataTable({
            processing: true,
            serverSide: true,
            ajax:{
                url: "{{ url('bundle-stock/dtable-bundle-transaction') }}",
                data: function(d) {
                    d.filter_type = $('#filter_type').val() || 'non_deleted';
                    d.transaction_type = $('#transaction_type').val();
                    }
                },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'serial_number', name: 'serial_number'},
                {data: 'gl_number', name: 'gl_number'},
                {data: 'table_number', name: 'table_number'},
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
        $('#transaction_type').change(function(event) {
            $('#transaction_history_table').DataTable().ajax.reload(null, false);
        });
    });

    async function delete_bundle_stock_transaction(id, filterType){
        swal_data = {
            title: filterType !== "soft_deleted" ? "You only have 30 minutes to retrieve the ticket back after delete it!" : "Bundle stock transaction will be deleted permanently!",
            icon: "warning",
            confirmButton: "Delete",
            confirmButtonClass: "btn-danger",
            cancelButtonClass: "btn-secondary"
        };
        let confirm_delete = await swal_confirm(swal_data);
        if(!confirm_delete) { return false; }

        let url_delete = filterType !== "soft_deleted" ? "{{ url('/bundle-stock/transaction-history/soft-delete') }}"+'/'+id : "{{ url('/bundle-stock/transaction-history/delete') }}"+'/'+id;

        fetch_data = {
            url : url_delete,
            method: "DELETE",
            token: token
         };

        result = await using_fetch_v2(fetch_data);

        if(result.status == "success"){
            swal_info({
                title : result.message,
            });

            setTimeout(function() {
                $('#transaction_history_table').DataTable().ajax.reload();
            }, 2000); // Delay 2 detik
            
        } else {
            swal_failed({ title: result.message });
        }
    }
</script>
@endpush
