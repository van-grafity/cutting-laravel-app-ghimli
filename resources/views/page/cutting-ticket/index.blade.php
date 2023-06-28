@extends('layouts.master')

@section('title', 'Cutting Ticket')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="content-title text-center">
                        <h3>Cutting Order Ticket</h3>
                    </div>
                    <div class="d-flex justify-content-end mb-1">
                        <a href="{{ route('cutting-ticket.create') }}" class="btn btn-success mb-2" id="btn_modal_create">Create</a>
                    </div>

                    <table class="table table-bordered table-hover" id="cutting_ticket_table">
                        <thead class="">
                            <tr>
                                <th scope="col" style="width: 20%;">Serial Number</th>
                                <th scope="col">Table No.</th>
                                <th scope="col" style="width: 10%;">Action</th>
                            </tr>
                        </thead>
                        <!-- <tbody>
                        </tbody> -->
                    </table>    
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
                            <div class="card mt-5 mb-3 mx-auto text-center" style="width:200px;">
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
                    <button type="button" class="btn btn-primary" data-dismiss="modal" style="width:100px;">OK</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('js')
<script type="text/javascript">
    const detail_ticket_url ='{{ route("cutting-ticket.show",":id") }}';

    const show_detail_ticket = async (ticket_id) => {

        let url_detail_ticket = detail_ticket_url.replace(':id',ticket_id);
        result = await get_using_fetch(url_detail_ticket);

        $('#detail_ticket_number').text(result.ticket_number)
        $('#detail_size').text(result.size)
        $('#detail_no_laying_sheet').text(result.no_laying_sheet)
        $('#detail_table_number').text(result.table_number)
        $('#detail_gl_number').text(result.gl_number)
        $('#detail_buyer').text(result.buyer)
        $('#detail_style').text(result.style)
        $('#detail_color').text(result.color)
        $('#detail_layer').text(result.layer)
        $('#detail_fabric_roll').text(result.fabric_roll)
        $('#detail_fabric_po').text(result.fabric_po)
        $('#detail_fabric_type').text(result.fabric_type)
        $('#detail_fabric_cons').text(result.fabric_cons)

        $('.qr-wrapper').html(`<img src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=${result.ticket_number}" alt="">`)

        $('#modal_formLabel').text("Detail")
        $('#btn_submit').text("OK")
        $('#modal_form').modal('show')
    }
</script>

<script type="text/javascript">
    $(function (e) {
        $('#cutting_ticket_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('/cutting-ticket-data') }}",
            columns: [
                {data: 'ticket_number', name: 'ticket_number'},
                {data: 'table_number', name: 'table_number'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            lengthChange: true,
            searching: true,
            autoWidth: false,
            responsive: true,
        });
    });
</script>

<script type="text/javascript">
$(document).ready(function() {


});
</script>

<script type="text/javascript">
    function delete_ticket(id){
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this ticket!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('/cutting-ticket/delete') }}"+'/'+id,
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
                            )
                            $('#cutting_ticket_table').DataTable().ajax.reload();
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