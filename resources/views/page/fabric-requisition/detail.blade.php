@extends('layouts.master')

@section('title', 'Fabric Requisition Detail')

@section('content')
<div class="container">
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
                                            <td>{{ $fabric_requisition->serial_number }}</td>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="text-left">
                                    <tbody class="align-top">
                                        <tr>
                                            <td>GL</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $fabric_requisition->gl_number }}</td>
                                        </tr>
                                        <tr>
                                            <td>Table No</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $fabric_requisition->table_number }}</td>
                                        </tr>
                                        <tr>
                                            <td>Style</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $fabric_requisition->style }}</td>
                                        </tr>
                                        <tr>
                                            <td>Fabric P/O</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $fabric_requisition->fabric_po }}</td>
                                        </tr>
                                        <tr>
                                            <td>No. Laying Sheet</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $fabric_requisition->no_laying_sheet}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table>
                                    <tbody class="align-top">
                                        <tr>
                                            <td>Fabric Type</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $fabric_requisition->fabric_type }}</td>
                                        </tr>
                                        <tr>
                                            <td>Color</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $fabric_requisition->color }}</td>
                                        </tr>
                                        <tr>
                                            <td>Quantity Required</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $fabric_requisition->quantity_required }}</td>
                                        </tr>
                                        <tr>
                                            <td>Quantity Issued</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $fabric_requisition->quantity_issued }}</td>
                                        </tr>
                                        <tr>
                                            <td>Difference</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $fabric_requisition->difference }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="qr-wrapper">
                                    <img src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl={{ $fabric_requisition->serial_number }}" alt="">
                                </div>
                            </div>
                        </div>

                    </div>

                    <hr style="border-top:2px solid #bbb" class="py-3">

                    <div class="content-title text-center">
                        <h3>Fabric Requisition Detail List</h3>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <a href="javascript:void(0);" class="btn btn-success mb-2 d-none" id="btn_modal_create">Create</a>
                    </div>

                    <table class="table align-middle table-bordered table-hover">
                        <thead class="">
                            <tr>
                                <th scope="col">No. </th>
                                <th scope="col">Place No</th>
                                <th scope="col">Width</th>
                                <th scope="col">Weight</th>
                                <th scope="col">Layer</th>
                                <th scope="col">Balanced End</th>
                                <th scope="col">Operator</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                    </table>
                    
                    <div class="row mt-10rem">
                        <div class="col-md-12 text-right">
                            <a href="{{ url('/fabric-requisition') }}" class="btn btn-secondary shadow-sm">back</a>
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
    const fabric_requisition_detail_url ='{{ route("fabric-requisition.detail",":id") }}';
    
</script>
<script type="text/javascript">
$(document).ready(function(){
    $('#btn_modal_create').click((e) => {
        $('#modal_formLabel').text("Add Fabric Requisition Table")
        $('#btn_submit').text("Add Fabric Requisition Table")
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

    async function show_detail(id) {
        let url_fabric_requisition_detail = fabric_requisition_detail_url.replace(':id',id);
        result = await using_fetch(url_fabric_requisition_detail,'', "GET");
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

        $('#modal_formLabel').text("Detail")
        $('#btn_submit').text("OK")
        $('#modal_form').modal('show')
    }

</script>
@endpush('js')