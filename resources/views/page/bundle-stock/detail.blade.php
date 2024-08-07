@extends('layouts.master')

@section('title', 'Transaction History Detail')

@section('content')
<style>
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="detail-section my-1 px-5">
                        <div class="row">
                            <div class="col-sm-8">
                                <table>
                                    <thead>
                                        <tr style="font-weight:700; font-size:20px;">
                                            <td>NO</td>
                                            <td>:</td>
                                            <td>{{ $bundle_stock_header['serial_number'] }}</td>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <table class="text-left">
                                    <tbody>
                                        <tr>
                                            <td>GL No.</td>
                                            <td class="pl-3">:</td>
                                            <td>{{ $bundle_stock_header['gl_number'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Location Destination</td>
                                            <td class="pl-3">:</td>
                                            <td>{{ $bundle_stock_header['location'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Transaction Type</td>
                                            <td class="pl-3">:</td>
                                            <td>{{ $bundle_stock_header['transaction_type'] }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-7">
                                <table>
                                    <tbody class="align-top">
                                        <tr>
                                            <td>Style</td>
                                            <td class="pl-3">:</td>
                                            <td>{{ $bundle_stock_header['style_no'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Created Date</td>
                                            <td class="pl-3">:</td>
                                            <td>{{ $bundle_stock_header['date']}}</td>
                                        </tr>
                                        <tr>
                                            <td>Total Stock</td>
                                            <td class="pl-3">:</td>
                                            <td>{{ $bundle_stock_header['total_stock'] }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <hr style="border-top:2px solid #bbb" class="py-3">

                    <div class="content-title text-center">
                        <h3>Cutting Ticket List</h3>
                    </div>
                    <table class="table table-bordered table-hover text-center" id="cutting_ticket_list_table">
                        <thead class="">
                            <tr>
                                <th width="5%;">No.</th>
                                <th width="5%;">No Ticket</th>
                                <th width="30%;">Serial Number</th>
                                <th width="15%;">Buyer</th>
                                <th width="5%;">GL Number</th>
                                <th width="20%;">Color</th>
                                <th width="5%;">Size</th>
                                <th width="5%;">Layer</th>
                            </tr>
                        </thead>
                    </table>

                    <div class="row mt-10rem">
                        <div class="col-md-12 text-right">
                            <a href="{{ url('bundle-stock/transaction-history') }}" class="btn btn-secondary shadow-sm">back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
     $(function (e) {
        var id = @json($bundle_stock_header['bundle_stock_transaction_id']);
        $('#cutting_ticket_list_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('bundle-stock/transaction-history/detail/dtable-ticket-list/:id') }}".replace(':id', id),
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'ticket_number', name: 'ticket_number'},
                {data: 'serial_number', name: 'serial_number'},
                {data: 'buyer_name', name: 'buyer_name'},
                {data: 'gl_number', name: 'gl_number'},
                {data: 'color', name: 'color'},
                {data: 'size', name: 'size'},
                {data: 'layer', name: 'layer'},
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
    });
</script>
@endpush
