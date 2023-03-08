@extends('layouts.master')

@section('title', 'Cutting Ticket')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="content-title text-center">
                        <h3>Cutting Ticket List</h3>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="search-box me-2 mb-2 d-inline-block">
                            <div class="position-relative">
                                <input type="text" class="form-control searchTable" placeholder="Search">
                                <i class="bx bx-search-alt search-icon"></i>
                            </div>
                        </div>
                        <a href="{{ route('cutting-ticket.createTicket') }}" class="btn btn-success mb-2" id="btn_modal_create">Create</a>
                    </div>

                    <table class="table align-middle table-nowrap table-hover">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="">No. </th>
                                <th scope="col" class="">Ticket Number</th>
                                <th scope="col" class="">COR No.</th>
                                <th scope="col" class="">Table No.</th>
                                <th scope="col" class="">GL</th>
                                <th scope="col" class="">Color</th>
                                <th scope="col" class="">Size</th>
                                <th scope="col" class="">Layer</th>
                                <th scope="col" class="">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>01</td>
                                <td>CT-62843-026-001</td>
                                <td>62843-026</td>
                                <td>026</td>
                                <td>62843-00</td>
                                <td>MED HEATHER GREY H125R (053)</td>
                                <td>XS</td>
                                <td>11</td>
                                <td>
                                    <a href="" class="btn btn-primary btn-sm btn-print-ticket">Print</a>
                                    <a href="javascript:void(0)" class="btn btn-info btn-sm btn-ticket-detail">Detail</a>
                                </td>
                            </tr>
                            <tr>
                                <td>02</td>
                                <td>CT-62843-026-002</td>
                                <td>62843-026</td>
                                <td>026</td>
                                <td>62843-00</td>
                                <td>MED HEATHER GREY H125R (053)</td>
                                <td>XS</td>
                                <td>12</td>
                                <td>
                                    <a href="" class="btn btn-primary btn-sm btn-print-ticket">Print</a>
                                    <a href="javascript:void(0)" class="btn btn-info btn-sm btn-ticket-detail">Detail</a>
                                </td>
                            </tr>
                            <tr>
                                <td>03</td>
                                <td>CT-62843-026-003</td>
                                <td>62843-026</td>
                                <td>026</td>
                                <td>62843-00</td>
                                <td>MED HEATHER GREY H125R (053)</td>
                                <td>XS</td>
                                <td>10</td>
                                <td>
                                    <a href="" class="btn btn-primary btn-sm btn-print-ticket">Print</a>
                                    <a href="javascript:void(0)" class="btn btn-info btn-sm btn-ticket-detail">Detail</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>    
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal Section -->
<div class="modal fade" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="modal_formLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_formLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="detail-section my-5 px-5">
                    <div class="row">
                        <div class="col-md-5">
                            <table class="text-left">
                                <tbody class="align-top">
                                    <tr style="font-weight:800;">
                                        <td>Ticket Number</td>
                                        <td class="pl-4">:</td>
                                        <td>CT-62843-026</td>
                                    </tr>
                                    <tr>
                                        <td>Size</td>
                                        <td class="pl-4">:</td>
                                        <td>XS</td>
                                    </tr>
                                    <tr>
                                        <td>COR Number</td>
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
                        <div class="col-md-7">
                            <table style="empty-cells: show;">
                                <tbody class="align-top">
                                    <tr style="height:50">
                                        <td colspan="3"></td>
                                    </tr>
                                    <tr>
                                        <td>Layer</td>
                                        <td class="pl-4">:</td>
                                        <td>11</td>
                                    </tr>
                                    <tr>
                                        <td>Fabric Roll No</td>
                                        <td class="pl-4">:</td>
                                        <td>34</td>
                                    </tr>
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
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card mt-5 mb-3 text-center" style="width:200px;">
                                <div class="title-qr pt-3" style="font-size:20px; font-weight: 600;">
                                    QR Code
                                </div>
                                <img src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=CT-62843-026" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-md-12 text-right">
                    <button type="button" class="btn bg-cyan" style="width:100px;">Print QR</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" style="width:100px;">OK</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('js')
<script type="text/javascript">
$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.btn-ticket-detail').click((e) => {
        $('#modal_formLabel').text("Detail")
        $('#btn_submit').text("OK")
        $('#modal_form').modal('show')
    })

</script>
@endpush