@extends('layouts.master')

@section('title', 'Laying Planning Detail')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    
                    <div class="detail-section my-5 px-5">
                        <div class="row">
                            <div class="col-md-4">
                                <table class="text-left">
                                    <tbody>
                                        <tr>
                                            <td>GL No.</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $data->gl->gl_number }}</td>
                                        </tr>
                                        <tr>
                                            <td>Buyer</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $data->buyer->name }}</td>
                                        </tr>
                                        <tr>
                                            <td>Style</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $data->style->style }}</td>
                                        </tr>
                                        <tr>
                                            <td>Color</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $data->color->color }}</td>
                                        </tr>
                                        <tr>
                                            <td>Order Qty</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $data->order_qty }} Pcs</td>
                                        </tr>
                                        <tr>
                                            <td>Total Qty</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $data->order_qty * 25 }} Pcs</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-8">
                                <table>
                                    <tbody class="align-top">
                                        <tr>
                                            <td>Fabric P/O</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $data->fabric_po }}</td>
                                        </tr>
                                        <tr>
                                            <td>Fabric Type</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $data->fabricType->description }}</td>
                                        </tr>
                                        <tr>
                                            <td>Fabric Consumpition</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $data->fabricCons->description }}</td>
                                        </tr>
                                        <tr>
                                            <td>Description</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $data->style->description }}</td>
                                        </tr>
                                        <tr>
                                            <td>Delivery Date</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $data->delivery_date }}</td>
                                        </tr>
                                        <tr>
                                            <td>Plan Date</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $data->plan_date }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                    <hr style="border-top:2px solid #bbb" class="py-3">

                    <div class="content-title text-center">
                        <h3>Cutting Table List</h3>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="search-box me-2 mb-2 d-inline-block">
                            <div class="position-relative">
                                <input type="text" class="form-control searchTable" placeholder="Search">
                                <i class="bx bx-search-alt search-icon"></i>
                            </div>
                        </div>
                        <a href="javascript:void(0);" class="btn btn-success mb-2" id="btn_modal_create">Create</a>
                    </div>

                    <table class="table align-middle table-nowrap table-hover">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="">No. </th>
                                <th scope="col" class="">No Laying Sheet</th>
                                <th scope="col" class="">Total Qty</th>
                                <th scope="col" class="">Marker Code</th>
                                <th scope="col" class="">Marker Length</th>
                                <th scope="col" class="">Total Length</th>
                                <th scope="col" class="">Layer Qty</th>
                                <th scope="col" class="">Status</th>
                                <th scope="col" class="">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cuttingTableList as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->no_laying_sheet }}</td>
                                <td>{{ $item->total_qty }}</td>
                                <td>{{ $item->marker_code }}</td>
                                <td>{{ $item->marker_length }}</td>
                                <td>{{ $item->total_length }}</td>
                                <td>{{ $item->layer_qty }}</td>
                                <td>
                                    @if ($item->status->id == 1)
                                        <span class="badge bg-soft-success text-success">Done</span>
                                    @elseif ($item->status->id == 2)
                                        <span class="badge bg-soft-warning text-warning">On Progress</span>
                                    @else
                                        <span class="badge bg-soft-danger text-danger">Not Yet</span>
                                    @endif
                                <td>
                                    <a href="javascript:void(0);" class="btn btn-sm btn-primary btn-edit" data-id="{{ $item->id }}">Edit</a>
                                    <a href="javascript:void(0);" class="btn btn-sm btn-danger btn-delete" data-id="{{ $item->id }}">Delete</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <div class="row mt-10rem">
                        <div class="col-md-12 text-right">
                            <a href="{{ url('/laying-planning') }}" class="btn btn-secondary shadow-sm">back</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Section -->
<div class="modal fade" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="modal_formLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_formLabel">Add GL</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="POST" class="custom-validation" enctype="multipart/form-data" id="gl_form">
                @csrf
                <div class="modal-body">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="layer_qty">Layer Quantity</label>
                            <input type="text" class="form-control" id="layer_qty" name="layer_qty" placeholder="Enter Layer Quantity">
                        </div>
                        <div>
                            <h4>Marker</h4>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="marker_code">Code</label>
                                    <input type="text" class="form-control" id="marker_code" name="marker_code" placeholder="Code">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="marker_yard">Yard</label>
                                    <input type="text" class="form-control" id="marker_yard" name="marker_yard" placeholder="Yard">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="marker_inch">Inch</label>
                                    <input type="text" class="form-control" id="marker_inch" name="marker_inch" placeholder="Inch">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="marker_length">Length</label>
                                    <input type="text" class="form-control" id="marker_length" name="marker_length" placeholder="Length">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="marker_total_length">Total Length</label>
                                    <input type="text" class="form-control" id="marker_total_length" name="marker_total_length" placeholder="Total Length">
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4>Ratio</h4>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="ratio_s">S</label>
                                    <input type="text" class="form-control" id="ratio_s" name="ratio_s">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="ratio_m">M</label>
                                    <input type="text" class="form-control" id="ratio_m" name="ratio_m">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="ratio_l">L</label>
                                    <input type="text" class="form-control" id="ratio_l" name="ratio_l">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="ratio_xl">XL</label>
                                    <input type="text" class="form-control" id="ratio_xl" name="ratio_xl">
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4>Qty Each Size</h4>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="qty_size_s">S</label>
                                    <input type="text" class="form-control" id="qty_size_s" name="qty_size_s">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="qty_size_m">M</label>
                                    <input type="text" class="form-control" id="qty_size_m" name="qty_size_m">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="qty_size_l">L</label>
                                    <input type="text" class="form-control" id="qty_size_l" name="qty_size_l">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="qty_size_xl">XL</label>
                                    <input type="text" class="form-control" id="qty_size_xl" name="qty_size_xl">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="qty_size_all">Total All Size</label>
                                    <input type="text" class="form-control" id="qty_size_all" name="qty_size_all" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END .card-body -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btn_submit">Add GL</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')

<script type="text/javascript">
$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#btn_modal_create').click((e) => {
        $('#modal_formLabel').text("Add Cutting Table")
        $('#btn_submit').text("Add Cutting Table")
        $('#gl_form').find("input[type=text], textarea").val("");
        $('#gl_form').find('input[name="_method"]').remove();
        $('#modal_form').modal('show')
    })


})
</script>

<script type="text/javascript">
    function reset_form(data = {}) {
        $('#modal_create_form').text(data.title);
        $('#btn_submit').text(data.btn_text);
        $('#create_form').find("input[type=text], textarea").val("");
        $('#create_form').find('input[name="_method"]').remove();
        $('#modal_form').modal('show')
    }
</script>
@endpush('js')