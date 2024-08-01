@extends('layouts.master')

@section('title', 'Cut Piece Transfer Note Detail')

@section('content')
<style>
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="detail-section my-5 px-5">
                        <div class="row">
                            <div class="col-sm-8">
                                <table>
                                    <thead>
                                        <tr style="font-weight:700; font-size:20px;">
                                            <td>NO</td>
                                            <td>:</td>
                                            <td>{{ $bundle_stock_transaction_header->serial_number }}</td>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            {{-- <div class="col-md-4 text-right">
                                <div class="btn-group">
                                    <div class="dropdown">
                                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Action
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="">
                                            <a class="dropdown-item" href="{{ route('bundle-transfer-note.print', $bundle_stock_transaction_header->transfer_note_id) }}" target="_blank">Print Transfer Note</a>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <table class="text-left">
                                    <tbody>
                                        <tr>
                                            <td>GL No.</td>
                                            <td class="pl-3">:</td>
                                            <td>{{ $bundle_stock_transaction_header->gl_number }}</td>
                                        </tr>
                                        <tr>
                                            <td>Location Destination</td>
                                            <td class="pl-3">:</td>
                                            <td>{{ $bundle_stock_transaction_header->location }}</td>
                                        </tr>
                                        <tr>
                                            <td>Transaction Type</td>
                                            <td class="pl-3">:</td>
                                            <td>{{ $bundle_stock_transaction_header->transaction_type }}</td>
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
                                            <td>{{ $bundle_stock_transaction_header->style_no }}</td>
                                        </tr>
                                        <tr>
                                            <td>Created Date</td>
                                            <td class="pl-3">:</td>
                                            <td>{{ $bundle_stock_transaction_header->date }}</td>
                                        </tr>
                                        <tr>
                                            <td>Total Stock</td>
                                            <td class="pl-3">:</td>
                                            <td>{{ $bundle_stock_transaction_header->total_stock }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <hr style="border-top:2px solid #bbb" class="py-3">

                    <div class="content-title text-center">
                        <h3>Transaction History Detail</h3>
                    </div>
                    <table class="table table-bordered table-hover text-center" id="transfer_note_detail_table">
                        <thead class="">
                            <tr>
                                <th colspan="1" rowspan="2" class="align-middle">No.</th>
                                <th colspan="1" rowspan="2" class="align-middle">Color</th>
                                <th colspan="1" rowspan="2" class="align-middle">Table Number</th>
                                <th colspan="{{ count($size_list) }}" rowspan="1" width="10%;" class="align-middle">Size</th>
                                <th colspan="1" rowspan="2" class="align-middle">Total</th>
                            </tr>
                            <tr>
                                @foreach($size_list as $size)
                                <th colspan="1" rowspan="1" width="10%;" class="align-middle">{{ $size->size }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bundle_stock_transaction_detail as $key => $detail)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $detail->color }}</td>
                                <td>{{ $detail->table_number }}</td>
                                @foreach($detail->qty_per_size as $per_size)
                                    <td>{{ $per_size->qty }}</td>
                                @endforeach
                                <td>{{ $detail->total_qty }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="row mt-10rem">
                        <div class="col-md-12 text-right">
                            <a href="{{ url('bundle-transfer-note') }}" class="btn btn-secondary shadow-sm">back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
