@extends('layouts.master')

@section('title', 'Cutting Order Record Detail')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    
                    <div class="detail-section my-5 px-5">
                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <table>
                                    <thead>
                                        <tr style="font-weight:700; font-size:20px;">
                                            <td>NO</td>
                                            <td>:</td>
                                            <td><a href="{{ route('laying-planning.show',$cutting_order->laying_planning_id) }}" style="color:blue;">{{ $cutting_order->serial_number }}</a></td>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <table class="text-left">
                                    <tbody class="align-top">
                                        <tr>
                                            <td>No. Laying Sheet</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $cutting_order->no_laying_sheet}}</td>
                                        </tr>
                                        <tr>
                                            <td>Table No</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $cutting_order->table_number }}</td>
                                        </tr>
                                        <tr>
                                            <td>GL</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $cutting_order->gl_number }}</td>
                                        </tr>
                                        <tr>
                                            <td>Buyer</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $cutting_order->buyer }}</td>
                                        </tr>
                                        <tr>
                                            <td>Style</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $cutting_order->style }}</td>
                                        </tr>
                                        <tr>
                                            <td>Color</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $cutting_order->color }}</td>
                                        </tr>
                                        <tr>
                                            <td>Created By</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $cutting_order->created_by }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <table>
                                    <tbody class="align-top">
                                        <tr>
                                            <td>Fabric P/O</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $cutting_order->fabric_po }}</td>
                                        </tr>
                                        <tr>
                                            <td>Fabric Type</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $cutting_order->fabric_type }}</td>
                                        </tr>
                                        <tr>
                                            <td>Fabric Consumpition</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $cutting_order->fabric_cons }} </td>
                                        </tr>
                                        <tr>
                                            <td>Marker Length</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $cutting_order->marker_length }}</td>
                                        </tr>
                                        <tr>
                                            <td>Marker Ratio</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $cutting_order->size_ratio }}</td>
                                        </tr>
                                        <tr>
                                            <td>Marker Code</td>
                                            <td class="pl-4">:</td>
                                            <td>
                                                @if($cutting_order->marker_code == 'PILOT RUN')
                                                    @if($cutting_order->is_pilot_run == true)
                                                        <span class="badge badge-success"> {{ $cutting_order->marker_code }} </span> <b>Approved</b> ({{ $cutting_order->pilot_run }})
                                                    @else
                                                        <span class="badge badge-warning"> {{ $cutting_order->marker_code }} </span>
                                                    @endif
                                                @else
                                                    <span class="badge badge-primary"> {{ $cutting_order->marker_code }} </span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Layer</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $cutting_order->layer }}</td>
                                        </tr>
                                        <tr>
                                            <td>Status Layer</td>
                                            <td class="pl-4">:</td>
                                            <td>
                                                @if($cutting_order->status_layer == 'completed')
                                                    <span class="badge badge-success"> Sudah Layer </span>
                                                @elseif($cutting_order->status_layer == 'over Layer')
                                                    <span class="badge badge-danger"> Over Layer </span>
                                                @elseif($cutting_order->status_layer == 'on progress')
                                                    <span class="badge badge-info"> Sedang Layer </span>
                                                @else
                                                    <span class="badge badge-warning"> Belum Layer </span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Status Cut</td>
                                            <td class="pl-4">:</td>
                                            <td>
                                                @if($cutting_order->status_cut == 'sudah')
                                                    <span class="badge badge-success"> Sudah Potong </span>
                                                @else
                                                    <span class="badge badge-warning"> Belum Potong </span>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="col-md-4 text-right">
                                @can('ppc')
                                    @if($cutting_order->marker_code == 'PILOT RUN')
                                        @if(count($cutting_order_detail) > 0)
                                                @if($cutting_order->is_pilot_run == true)
                                                    <a href="{{ route('cutting-order.approve-pilot-run',$cutting_order->id) }}" class="btn btn-danger shadow-sm">Reject</a>
                                                @else
                                                    <a href="{{ route('cutting-order.approve-pilot-run',$cutting_order->id) }}" class="btn btn-success shadow-sm">Approve</a>
                                                @endif
                                        @endif
                                    @endif
                                @endcan
                                <div class="btn-group">
                                    <div class="dropdown">
                                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Action
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                             <!-- <a class="dropdown-item" href="{{ route('cutting-order.print', $cutting_order->id) }}" target="_blank">Print Report</a>
                                             <a class="dropdown-item" href="{{ route('cutting-order.report', $cutting_order->id) }}" target="_blank">Print Nota</a>
                                            <a class="dropdown-item" href="{{ route('cutting-order.report', $cutting_order->id) }}" target="_blank">Print Report</a>
                                            @can('clerk')
                                                <a class="dropdown-item" href="javascript:void(0);" onclick="delete_cuttingOrder({{ $cutting_order->id }})" data-id="{{ $cutting_order->id }}">Delete</a>
                                            @endcan -->
                                            @if(Auth::user()->hasRole('super_admin'))
                                                <a class="dropdown-item" href="{{ route('cutting-order.print', $cutting_order->id) }}" target="_blank">Print</a>
                                            @endif
                                            @if(Auth::user()->hasRole('clerk-cutting'))
                                                @if($cutting_order->status_print == 1)
                                                    <a class="dropdown-item" href="javascript:void(0);" onclick="print_nota()">Print Ulang</a>
                                                @else
                                                    <a class="dropdown-item" href="{{ route('cutting-order.report', $cutting_order->id) }}" target="_blank">Print</a>
                                                @endif
                                            @endif
                                            @if(count($cutting_order_detail) > 0)
                                                <a class="dropdown-item" href="{{ route('cutting-order.report', $cutting_order->id) }}" target="_blank">Report</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="qr-wrapper">
                                    <img src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl={{ $cutting_order->serial_number }}" alt="">
                                </div>
                            </div>
                        </div>

                        

                    </div>

                    <hr style="border-top:2px solid #bbb" class="py-3">

                    <div class="content-title text-center">
                        <h3>Cutting Order Detail List</h3>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <a href="javascript:void(0);" class="btn btn-success mb-2 d-none" id="btn_modal_create">Create</a>
                    </div>

                    <table class="table align-middle table-bordered table-hover">
                        <thead class="">
                            <tr>
                                <th scope="col">No. </th>
                                <th scope="col">Roll No.</th>
                                <th scope="col">Batch No.</th>
                                <th scope="col" width='10px'>
                                    <span>
                                        Fabric Yardage (Sticker)
                                    </span>
                                </th>
                                <th scope="col">Weight</th>
                                <th scope="col">Layer</th>
                                <th scope="col">Actual Yardage</th>
                                <th scope="col">
                                <span>
                                    Balance End (yard)
                                </span>
                                </th>
                                <th scope="col">Operator</th>
                                <th scope="col">Layer Date</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <!-- const set_marker_length = () => {
                            let marker_yard = $('#marker_yard').val() ? parseFloat($('#marker_yard').val()) : 0;
                            let marker_inch = $('#marker_inch').val() ? parseFloat($('#marker_inch').val()) : 0;
                            let marker_length = (marker_yard + ((marker_inch + parseFloat($('#marker_allowence').val())) / 36)).toFixed(2);
                            $('#marker_length').val(marker_length);
                            set_marker_total_length();
                        }; -->

                        <tbody>
                            @foreach( $cutting_order_detail as $key => $detail )
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $detail->fabric_roll }}</td>
                                <td>{{ $detail->fabric_batch }}</td>
                                <td>{{ $detail->yardage }}</td>
                                <td>{{ $detail->weight }}</td>
                                <td>{{ $detail->layer }}</td>
                                <td><?php
                                    $actual = $detail->layer * ($cutting_order->marker_yards + (($cutting_order->marker_inches + 1) / 36));
                                    $actual = number_format($actual, 2, '.', '');
                                    echo $actual;
                                ?></td>
                                <td>{{ $detail->balance_end }}</td>
                                <td>{{ $detail->operator }}</td>
                                <td>{{ $detail->cutting_date }}</td>
                                <td>
                                    <a href="javascript:void(0);" class="btn btn-info btn-sm" onclick="show_detail({{ $detail->id }})">Detail</a>
                                    @can('super_admin')
                                        <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="delete_cutting_order_detail({{ $detail->id }})">Delete</a>
                                    @endcan
                                </td>
                                <td class="d-none">
                                    <a href="" class="btn btn-primary btn-sm btn-edit-layingPlanning">Edit</a>
                                </td>
                            </tr>
                            @endforeach
                            <tr class="spacer" style="height:5px;">
                                <td colspan="8"></td>
                            </tr>
                            <tr class="bg-dark mt-2">
                                <td colspan="3" class="text-right">Total</td>
                                <td>{{ $cutting_order->total_width }} Yard</td>
                                <td>{{ $cutting_order->total_weight }}</td>
                                <td class="bi bi-table" data-toggle="tooltip" data-placement="top" title="Total layer {{ $cutting_order->total_layer }} harus sama dengan layer actual planning">
                                    {{ $cutting_order->total_layer }}
                                    <span class="glyphicon glyphicon-magnet"></span>
                                </td>
                                <td><?php
                                    $total_actual = $cutting_order->total_layer * ($cutting_order->marker_yards + ($cutting_order->marker_inches / 36));
                                    $total_actual = number_format($total_actual, 2, '.', '');
                                    echo $total_actual . " Yard";
                                ?></td>
                                <td colspan="4">{{ $cutting_order->total_balance_end }} Yard</td>
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
<style>
    .glyphicon-magnet:before {
        content: "\e134";
    }
</style>

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
                                        <td>Fabric Roll</td>
                                        <td class="pl-4">:</td>
                                        <td id="fabric_roll"></td>
                                    </tr>
                                    <tr>
                                        <td>Fabric Batch</td>
                                        <td class="pl-4">:</td>
                                        <td id="fabric_batch"></td>
                                    </tr>
                                    <tr>
                                        <td>Color</td>
                                        <td class="pl-4">:</td>
                                        <td id="color"></td>
                                    </tr>
                                    <tr>
                                        <td>Yardage</td>
                                        <td class="pl-4">:</td>
                                        <td id="yardage"></td>
                                    </tr>
                                    <tr>
                                        <td>Weight</td>
                                        <td class="pl-4">:</td>
                                        <td id="weight"></td>
                                    </tr>
                                    <tr>
                                        <td>Layer</td>
                                        <td class="pl-4">:</td>
                                        <td id="layer"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-7">
                            <table style="">
                                <tbody class="align-top">
                                    <tr style="height:30">
                                        <td colspan="3"></td>
                                    </tr>
                                    <tr>
                                        <td>Joint</td>
                                        <td class="pl-4">:</td>
                                        <td id="joint"></td>
                                    </tr>
                                    <tr>
                                        <td>Balance End</td>
                                        <td class="pl-4">:</td>
                                        <td id="balance_end"></td>
                                    </tr>
                                    <tr>
                                        <td>Remarks</td>
                                        <td class="pl-4">:</td>
                                        <td id="remarks"></td>
                                    </tr>
                                    <tr>
                                        <td>Operator</td>
                                        <td class="pl-4">:</td>
                                        <td id="operator"></td>
                                    </tr>
                                    <tr>
                                        <td>Layer Date</td>
                                        <td class="pl-4">:</td>
                                        <td id="cutting_date"></td>
                                    </tr>
                                </tbody>
                            </table>
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
    const cutting_order_detail_url ='{{ route("cutting-order.detail",":id") }}';
    
</script>
<script type="text/javascript">
$(document).ready(function(){
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
    let delete_cutting_order_detail_url = '{{ route("cutting-order.detail-delete",":id") }}';
    let print_nota_url = '{{ route("cutting-order.report",":id") }}';

    async function print_nota() {
        Swal.fire({
            title: 'Nota ini sudah pernah di print !',
            text: "Segera hubungi Cutting Manager mencetak ulang.",
            icon: 'warning',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'OK'
        }).then((result) => {})
    }

    function reset_form(data = {}) {
        $('#modal_create_form').text(data.title);
        $('#btn_submit').text(data.btn_text);
        $('#create_form').find("input[type=text], textarea").val("");
        $('#create_form').find('input[name="_method"]').remove();
        $('#modal_form').modal('show')
    }

    async function delete_cutting_order_detail(id) {
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Data Roll akan di hapus dari Cutting Order Record ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                let url_delete_cutting_order_detail = delete_cutting_order_detail_url.replace(':id',id);
                using_fetch(url_delete_cutting_order_detail,'', "GET");
                window.location.reload();
            }
        })
    }

    async function show_detail(id) {
        let url_cutting_order_detail = cutting_order_detail_url.replace(':id',id);
        result = await using_fetch(url_cutting_order_detail,'', "GET");
        if(result.status !== "success") {
            return false;
        }

        $('#fabric_roll').text(result.data.fabric_roll)
        $('#fabric_batch').text(result.data.fabric_batch)
        $('#color').text(result.data.color.color)
        $('#yardage').text(result.data.yardage)
        $('#weight').text(result.data.weight)
        $('#layer').text(result.data.layer)
        $('#joint').text(result.data.joint)
        $('#balance_end').text(result.data.balance_end)
        $('#remarks').text(result.data.remarks)
        $('#operator').text(result.data.operator)
        $('#cutting_date').text(result.data.cutting_date)

        $('#modal_formLabel').text("Detail")
        $('#btn_submit').text("OK")
        $('#modal_form').modal('show')
    }

    async function approve_pilot_run(id) {
        let url_approve_pilot_run = approve_pilot_run_url.replace(':id',id);
        result = await using_fetch(url_approve_pilot_run,'', "GET");
        if(result.status !== "success") {
            return false;
        }

        $('#fabric_roll').text(result.data.fabric_roll)
        $('#fabric_batch').text(result.data.fabric_batch)
        $('#color').text(result.data.color.color)
        $('#yardage').text(result.data.yardage)
        $('#weight').text(result.data.weight)
        $('#layer').text(result.data.layer)
        $('#joint').text(result.data.joint)
        $('#balance_end').text(result.data.balance_end)
        $('#remarks').text(result.data.remarks)
        $('#operator').text(result.data.operator)
        $('#cutting_date').text(result.data.cutting_date)

        $('#modal_formLabel').text("Detail")
        $('#btn_submit').text("OK")
        $('#modal_form').modal('show')
    }


</script>
@endpush('js')