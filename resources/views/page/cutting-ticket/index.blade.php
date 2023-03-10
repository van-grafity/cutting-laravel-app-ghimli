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
                                <th scope="col">No. </th>
                                <th scope="col">Ticket Number</th>
                                <th scope="col">No. Laying Sheet</th>
                                <th scope="col">Table No.</th>
                                <th scope="col">GL</th>
                                <th scope="col">Color</th>
                                <th scope="col">Size</th>
                                <th scope="col">Layer</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $ticket)
                            <tr>
                                <td>{{ $ticket->no }}</td>
                                <td>{{ $ticket->ticket_number }}</td>
                                <td>{{ $ticket->no_laying_sheet }}</td>
                                <td>{{ $ticket->table_number }}</td>
                                <td>{{ $ticket->gl_number }}</td>
                                <td>{{ $ticket->color }}</td>
                                <td>{{ $ticket->size }}</td>
                                <td>{{ $ticket->layer }}</td>
                                <td>
                                    <a href="" class="btn btn-primary btn-sm btn-print-ticket">Print</a>
                                    <a href="javascript:void(0)" class="btn btn-info btn-sm btn-ticket-detail" data-url="{{ route('cutting-ticket.show', $ticket->id) }}">Detail</a>
                                </td>
                            </tr>
                            @endforeach

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
                                        <td id="detail_ticket_number"></td>
                                    </tr>
                                    <tr>
                                        <td>Size</td>
                                        <td class="pl-4">:</td>
                                        <td id="detail_size"></td>
                                    </tr>
                                    <tr>
                                        <td>No. Laying Sheet</td>
                                        <td class="pl-4">:</td>
                                        <td id="detail_no_laying_sheet"></td>
                                    </tr>
                                    <tr>
                                        <td>Table No</td>
                                        <td class="pl-4">:</td>
                                        <td id="detail_table_number"></td>
                                    </tr>
                                    <tr>
                                        <td>GL</td>
                                        <td class="pl-4">:</td>
                                        <td id="detail_gl_number"></td>
                                    </tr>
                                    <tr>
                                        <td>Buyer</td>
                                        <td class="pl-4">:</td>
                                        <td id="detail_buyer"></td>
                                    </tr>
                                    <tr>
                                        <td>Style</td>
                                        <td class="pl-4">:</td>
                                        <td id="detail_style"></td>
                                    </tr>
                                    <tr>
                                        <td>Color</td>
                                        <td class="pl-4">:</td>
                                        <td id="detail_color"></td>
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
                                        <td id="detail_layer"></td>
                                    </tr>
                                    <tr>
                                        <td>Fabric Roll No</td>
                                        <td class="pl-4">:</td>
                                        <td id="detail_fabric_roll"></td>
                                    </tr>
                                    <tr>
                                        <td>Fabric P/O</td>
                                        <td class="pl-4">:</td>
                                        <td id="detail_fabric_po"></td>
                                    </tr>
                                    <tr>
                                        <td>Fabric Type</td>
                                        <td class="pl-4">:</td>
                                        <td id="detail_fabric_type"></td>
                                    </tr>
                                    <tr>
                                        <td>Fabric Consumpition</td>
                                        <td class="pl-4">:</td>
                                        <td id="detail_fabric_cons"></td>
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
                                <div class="qr-wrapper">
                                    <img src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=CT-62843-026" alt="">
                                </div>
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

    $('.btn-ticket-detail').click( async function (e) {

        let get_data_url = $(this).attr('data-url');
        console.log(get_data_url);
        if (get_data_url){
            await get_data_ajax(get_data_url);
        } else {
            alert("not found!");
        }

        
    })

</script>

<script>
    function get_data_ajax(get_data_url) {
        $.ajax({
            type:'GET',
            url:get_data_url,
            success:function(res){
                
                $('#detail_ticket_number').text(res.ticket_number)
                $('#detail_size').text(res.size)
                $('#detail_no_laying_sheet').text(res.no_laying_sheet)
                $('#detail_table_number').text(res.table_number)
                $('#detail_gl_number').text(res.gl_number)
                $('#detail_buyer').text(res.buyer)
                $('#detail_style').text(res.style)
                $('#detail_color').text(res.color)
                $('#detail_layer').text(res.layer)
                $('#detail_fabric_roll').text(res.fabric_roll)
                $('#detail_fabric_po').text(res.fabric_po)
                $('#detail_fabric_type').text(res.fabric_type)
                $('#detail_fabric_cons').text(res.fabric_cons)

                $('.qr-wrapper').html(`<img src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=${res.ticket_number}" alt="">`)

                $('#modal_formLabel').text("Detail")
                $('#btn_submit').text("OK")
                $('#modal_form').modal('show')
            }
        }).catch((err)=>{
            console.log(err);
        });
    }
</script>
@endpush