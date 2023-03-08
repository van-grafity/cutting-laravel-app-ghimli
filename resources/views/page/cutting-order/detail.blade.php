@extends('layouts.master')

@section('title', 'Cutting Order Record Detail')

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
                                    <tbody class="align-top">
                                        <tr>
                                            <td>No. Laying Sheet</td>
                                            <td class="pl-4">:</td>
                                            <td>62843-026</td>
                                        </tr>
                                        <tr>
                                            <td>Table No</td>
                                            <td class="pl-4">:</td>
                                            <td>26</td>
                                        </tr>
                                        <tr>
                                            <td>GL</td>
                                            <td class="pl-4">:</td>
                                            <td>62843-00</td>
                                        </tr>
                                        <tr>
                                            <td>Buyer</td>
                                            <td class="pl-4">:</td>
                                            <td>AEROPOSTALE</td>
                                        </tr>
                                        <tr>
                                            <td>Style</td>
                                            <td class="pl-4">:</td>
                                            <td>5243AU22</td>
                                        </tr>
                                        <tr>
                                            <td>Color</td>
                                            <td class="pl-4">:</td>
                                            <td>MED HEATHER GREY H125R (053)</td>
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
                                            <td>100048963</td>
                                        </tr>
                                        <tr>
                                            <td>Fabric Type</td>
                                            <td class="pl-4">:</td>
                                            <td>57% cotton 38 polyester 5%spandex pique 185gm/m</td>
                                        </tr>
                                        <tr>
                                            <td>Fabric Consumpition</td>
                                            <td class="pl-4">:</td>
                                            <td>
                                                BODY+Sleeves+top and under placket :5.62yds x 74" x 322gm (cuttable)- Ctn poly spandex pique
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Marker Length</td>
                                            <td class="pl-4">:</td>
                                            <td>5 yd 35" 12'</td>
                                        </tr>
                                        <tr>
                                            <td>Marker Ratio</td>
                                            <td class="pl-4">:</td>
                                            <td>XS = 3 | M = 1 | L = 4 | XL = 5</td>
                                        </tr>
                                        <tr>
                                            <td>Layer</td>
                                            <td class="pl-4">:</td>
                                            <td>80</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                    <hr style="border-top:2px solid #bbb" class="py-3">

                    <div class="content-title text-center">
                        <h3>Cutting Order Detail List</h3>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="search-box me-2 mb-2 d-inline-block">
                            <div class="position-relative">
                                <input type="text" class="form-control searchTable" placeholder="Search">
                                <i class="bx bx-search-alt search-icon"></i>
                            </div>
                        </div>
                        <a href="javascript:void(0);" class="btn btn-success mb-2 d-none" id="btn_modal_create">Create</a>
                    </div>

                    <table class="table align-middle table-nowrap table-hover">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="">No. </th>
                                <th scope="col" class="">Place No</th>
                                <th scope="col" class="">Color</th>
                                <th scope="col" class="">Width</th>
                                <th scope="col" class="">Weight</th>
                                <th scope="col" class="">Layer</th>
                                <th scope="col" class="">Operator</th>
                                <th scope="col" class="">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>01</td>
                                <td>28</td>
                                <td>MED HEATHER GREY H125R (053)</td>
                                <td>61</td>
                                <td>25.02</td>
                                <td>10</td>
                                <td>Juhri</td>
                                <td>
                                    <a href="" class="btn btn-primary btn-sm btn-edit-layingPlanning">Edit</a>
                                    <a href="javascript:void(0);" class="btn btn-danger btn-sm btn-delete-layingPlanning">Delete</a>
                                </td>
                            </tr>
                            <tr>
                                <td>02</td>
                                <td>5</td>
                                <td>MED HEATHER GREY H125R (053)</td>
                                <td>87</td>
                                <td>28.91</td>
                                <td>15</td>
                                <td>Jurhi</td>
                                <td>
                                    <a href="" class="btn btn-primary btn-sm btn-edit-layingPlanning">Edit</a>
                                    <a href="javascript:void(0);" class="btn btn-danger btn-sm btn-delete-layingPlanning">Delete</a>
                                </td>
                            </tr>
                            <tr>
                                <td>03</td>
                                <td>31</td>
                                <td>MED HEATHER GREY H125R (053)</td>
                                <td>74</td>
                                <td>24.16</td>
                                <td>12</td>
                                <td>Juhri</td>
                                <td>
                                    <a href="" class="btn btn-primary btn-sm btn-edit-layingPlanning">Edit</a>
                                    <a href="javascript:void(0);" class="btn btn-danger btn-sm btn-delete-layingPlanning">Delete</a>
                                </td>
                            </tr>
                            <tr class="spacer" style="height:5px;">
                                <td colspan="8"></td>
                            </tr>
                            <tr class="bg-dark mt-2">
                                <td colspan="3">Total</td>
                                <td>502</td>
                                <td>170.55</td>
                                <td>80</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div class="row mt-10rem">
                        <div class="col-md-12 text-right">
                            <a href="{{ url('/cutting-order') }}" class="btn btn-secondary shadow-sm">back</a>
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