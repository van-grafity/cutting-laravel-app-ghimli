@extends('layouts.master')
@section('title', 'Laying Planning Detail')
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                
                <div class="detail-section my-5 px-5">
                    <div class="row mb-3">
                        <div class="col-sm-8">
                            <table>
                                <thead>
                                    <tr style="font-weight:700; font-size:20px;">
                                        <td>
                                            @if($laying_planning->status_print == 1)
                                                <span class="dot dot-sm dot-success" data-toggle="tooltip" data-placement="top" title="Nota Planning Sudah di Print"></span>
                                            @endif
                                            NO
                                        </td>
                                        <td>:</td>
                                        <td>{{ $laying_planning->serial_number }}</td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        @if(Auth::user()->hasRole('super_admin') || Auth::user()->hasRole('cutter'))
                        <div class="col-md-4 text-right">
                            <div class="btn-group">
                                <div class="dropdown">
                                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="{{ route('laying-planning.duplicate', $laying_planning->id) }}">Duplicate</a>
                                        @if($laying_planning->laying_planning_type_id && $laying_planning->laying_planning_type_id == 1)
                                            <a class="dropdown-item" href="{{ route('laying-planning.create-planning-support', $laying_planning->id) }}" target="_blank">Create Planning Support</a>
                                        @endif
                                        
                                        @if($is_can_edit)
                                        <a class="dropdown-item" href="{{ route('laying-planning.edit', $laying_planning->id) }}">Edit</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <table class="text-left">
                                <tbody>
                                    <tr>
                                        <td>GL No.</td>
                                        <td class="pl-3">:</td>
                                        <td>{{ $laying_planning->gl->gl_number }}</td>
                                    </tr>
                                    <tr>
                                        <td>Buyer</td>
                                        <td class="pl-3">:</td>
                                        <td>{{ $laying_planning->gl->buyer->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Style</td>
                                        <td class="pl-3">:</td>
                                        <td>
                                            @foreach ($styles as $style)
                                                @if($style->id == $laying_planning->style_id)
                                                    {{ $style->style }}
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Color</td>
                                        <td class="pl-3">:</td>
                                        <td>{{ $laying_planning->color->color }}</td>
                                    </tr>
                                    <tr>
                                        <td>Order Qty</td>
                                        <td class="pl-3">:</td>
                                        <td>{{ $laying_planning->order_qty }} Pcs</td>
                                    </tr>
                                    <tr>
                                        <td>Total Qty</td>
                                        <td class="pl-3">:</td>
                                        <td>{{ $laying_planning->total_order_qty }} Pcs</td>
                                    </tr>
                                    <tr>
                                        <td>Planning Type</td>
                                        <td class="pl-3">:</td>
                                        <td>{{ $laying_planning->LayingPlanningType ? $laying_planning->LayingPlanningType->type : '-' }} </td>
                                    </tr>
                                    @if( $laying_planning->LayingPlanningType)
                                    <tr>
                                        <td>Planning Parent</td>
                                        <td class="pl-3">:</td>
                                        <td> 
                                            <a href="{{ route('laying-planning.show',$laying_planning->parent_laying_planning_id) }}" target="_blank">
                                                {{ $laying_planning->parentLayingPlanning->serial_number }}
                                            </a>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-7">
                            <table>
                                <tbody class="align-top">
                                    <tr>
                                        <td>Fabric P/O</td>
                                        <td class="pl-3">:</td>
                                        <td>{{ $laying_planning->fabric_po }}</td>
                                    </tr>
                                    <tr>
                                        <td>Fabric Type</td>
                                        <td class="pl-3">:</td>
                                        <td>{{ $laying_planning->fabricType->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Fabric Consumpition</td>
                                        <td class="pl-3">:</td>
                                        <td>{{ $laying_planning->fabricCons->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Description</td>
                                        <td class="pl-3">:</td>
                                        <td>
                                            @foreach ($styles as $style)
                                                @if($style->id == $laying_planning->style_id)
                                                    {{ $style->description }}
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Delivery Date</td>
                                        <td class="pl-3">:</td>
                                        <td>{{ $laying_planning->delivery_date }}</td>
                                    </tr>
                                    <tr>
                                        <td>Plan Date</td>
                                        <td class="pl-3">:</td>
                                        <td>{{ $laying_planning->plan_date }}</td>
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
                @canany(['clerk','cutter'])
                <div class="d-flex justify-content-end mb-1">
                    <div class="action-wrapper mr-auto">
                        @can('unprint-cor')
                            <button class="btn btn-primary btn-sm" disabled="disabled" onclick="unprint_cor()">
                                <i class="fas fa-print"></i> Undo Print COR
                            </button>
                        @endcan
                        @can('unprint-fbr')
                            <button class="btn bg-navy btn-sm" disabled="disabled" onclick="unprint_fbr()">
                                <i class="fas fa-print"></i> Undo Print FBR
                            </button>
                        @endcan
                    </div>
                    <a href="javascript:void(0);" class="btn btn-success mb-2" id="btn_modal_create">Create</a>
                    <a href="{{ route('cutting-order.print-multiple', $laying_planning->id) }}" class="btn btn-info mb-2 ml-2" id="print_multi_nota">Print Nota</a>
                    <a href="{{ route('fabric-requisition.print-multiple', $laying_planning->id) }}" class="btn btn-info mb-2 ml-2" id="print_multi_fabric">Print Fabric Req</a>
                </div>
                @endcan
                <table class="table align-middle table-nowrap table-hover">
                    <thead class="table-light">
                        <tr class="text-center">
                            @canany(['unprint-cor','unprint-fbr'])
                                <th scope="col">
                                    <div class="form-group mb-0">
                                        <div class="custom-control custom-checkbox">
                                            <input 
                                                id="print_checkbox_all" 
                                                class="custom-control-input checkbox-all-control" 
                                                type="checkbox"
                                            >
                                            <label for="print_checkbox_all" class="custom-control-label"></label>
                                        </div>
                                    </div>
                                </th>
                            @endcan
                            @canany(['laying-planning.manage','clerk'])
                                <th scope="col">COR</th>
                                <th scope="col">FBR</th>
                            @endcan
                            <th scope="col">Table No</th>
                            <th scope="col">Marker Code</th>
                            <th scope="col">Marker Length</th>
                            <th scope="col">Total Pcs</th>
                            <th scope="col">Total Yds Qty</th>
                            <th scope="col">Layer Qty</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($details as $detail)
                            <tr class="text-center">
                                @canany(['unprint-cor','unprint-fbr'])
                                <td>
                                    <div class="form-group mb-0">
                                        <div class="custom-control custom-checkbox">
                                            <input 
                                                id="print_checkbox_{{ $detail->id }}" 
                                                name="selected_item[]" 
                                                class="custom-control-input checkbox-print-control" 
                                                type="checkbox" 
                                                value="{{ $detail->id }}"
                                                onchange="checkbox_clicked()" 
                                            >
                                            <label for="print_checkbox_{{ $detail->id }}" class="custom-control-label"></label>
                                        </div>
                                    </div>
                                </td>
                                @endcan
                                @canany(['laying-planning.manage','clerk'])
                                <td>
                                    @if($detail->cor_status_print == 0 || Auth::user()->can('super_admin'))
                                        <div class="form-group mb-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="laying_planning_laying_planning_detail_ids[]" value="{{ $detail->id }}">
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                @endcan
                                @canany(['laying-planning.manage','clerk'])
                                <td>
                                    @if($detail->fr_status_print == 0 || Auth::user()->can('super_admin'))
                                        <div class="form-group mb-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="fbr_ids[]" value="{{ $detail->id }}">
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                @endcan
                                <td>
                                    @if(Auth::user()->hasRole('super_admin') || Auth::user()->hasRole('cutter') || Auth::user()->hasRole('pmr'))
                                    <div class="dropdown">
                                        @if($detail->cor_status_print == 1 || $detail->fr_status_print == 1)
                                            <span class="dot dot-sm dot-success" data-toggle="tooltip" data-placement="top" title="Salah satu nota Layer atau Fabric Req sudah di print"></span>
                                        @endif
                                        <a class="text-decoration-none dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">{{ $detail->table_number }}</a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            @if($detail->fr_id != null) 
                                                @if(!Auth::user()->hasRole('pmr'))
                                                <li><a class="dropdown-item" href="{{ route('fabric-requisition.show', $detail->fr_id) }}">Detail Fabric</a></li>
                                                @endif
                                            @endif
                                            @if($detail->cor_id != null)
                                                <li><a class="dropdown-item" href="{{ route('cutting-order.show', $detail->cor_id) }}">Detail Cutting</a></li>
                                            @endif
                                        </ul>
                                    </div>
                                    @else
                                        {{ $detail->table_number }}
                                    @endif
                                </td>
                                <td>{{ $detail->marker_code }}</td>
                                <td>{{ $detail->marker_length }}</td>
                                <td>{{ $detail->total_all_size }}</td>
                                <td>{{ $detail->total_length }}</td>
                                <td>{{ $detail->layer_qty }}</td>
                                <td>
                                    @if($detail->cuttingOrderRecord == null)
                                        @if(Auth::user()->hasRole('super_admin') || Auth::user()->hasRole('cutter'))
                                        <a href="javascript:void(0);" class="btn btn-primary btn-sm btn-detail-edit" data-id="{{ $detail->id }}" data-url="{{ route('laying-planning.detail-edit', $detail->id) }}">Edit</a>
                                        <a href="javascript:void(0);" class="btn btn-danger btn-sm btn-detail-delete" data-id="{{ $detail->id }}" data-url="{{ route('laying-planning.detail-delete', $detail->id) }}" >Delete</a>
                                        @endif
                                    @else
                                        @if($detail->cuttingOrderRecord->status_print == 1)
                                            @can('super_admin')
                                                <a href="javascript:void(0);" class="btn btn-primary btn-sm btn-detail-edit" data-id="{{ $detail->id }}" data-url="{{ route('laying-planning.detail-edit', $detail->id) }}">Edit</a>
                                                <a href="javascript:void(0);" class="btn btn-danger btn-sm btn-detail-delete" data-id="{{ $detail->id }}" data-url="{{ route('laying-planning.detail-delete', $detail->id) }}" >Delete</a>
                                            @endcan
                                        @else
                                            @if(Auth::user()->hasRole('super_admin') || Auth::user()->hasRole('cutter'))
                                                <a href="javascript:void(0);" class="btn btn-primary btn-sm btn-detail-edit" data-id="{{ $detail->id }}" data-url="{{ route('laying-planning.detail-edit', $detail->id) }}">Edit</a>
                                                <a href="javascript:void(0);" class="btn btn-danger btn-sm btn-detail-delete" data-id="{{ $detail->id }}" data-url="{{ route('laying-planning.detail-delete', $detail->id) }}" >Delete</a>
                                            @endif
                                        @endif
                                    @endif
                                    @if(Auth::user()->hasRole('super_admin') || Auth::user()->hasRole('cutter'))
                                    @if($detail->fr_status == 'disabled')
                                        <a href="{{ route('fabric-requisition.show', $detail->fr_id) }}" class="btn btn-sm btn-outline-info">Detail FBR</a>
                                    @else
                                        <a href="{{ route('fabric-requisition.createNota', $detail->id) }}" class="btn btn-sm btn-outline-secondary {{ $detail->fr_status }}">Create Fab</a>
                                    @endif
                                    @endif
                                    @if($detail->cor_status == 'disabled')
                                        <a href="{{ route('cutting-order.show', $detail->cor_id) }}" class="btn btn-sm btn-outline-info">Detail COR</a>
                                    @else
                                        <a href="{{ route('cutting-order.createNota', $detail->id) }}" class="btn btn-sm btn-outline-secondary {{ $detail->cor_status }}">Create COR</a>
                                    @endif
                                    @if(Auth::user()->hasRole('super_admin') || Auth::user()->hasRole('cutter'))
                                    <a href="javascript:void(0)" class="btn btn-sm btn-dark btn-detail-duplicate" data-id="{{ $detail->id }}">Duplicate</a>
                                    @endif
                                    
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="text-center">
                            <th class="text-left" colspan="3">Total :</th>
                            @canany(['unprint-cor','unprint-fbr'])
                             <th colspan="1"></th>
                            @endcan
                            @canany(['laying-planning.manage','clerk'])
                             <th colspan="2"></th>
                            @endcan
                            <th class="" colspan="1">{{ $laying_planning->total_pcs_all_table }} Pcs </th>
                            <th class="" colspan="1">{{ $laying_planning->total_length_all_table }} Yd </th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
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

<!-- Modal Section -->
<div class="modal fade" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="modal_formLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_formLabel">Add Cutting Table</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('laying-planning.detail-create') }}" method="POST" class="custom-validation" enctype="multipart/form-data" id="planning_detail_form">
                @csrf
                <input type="hidden" name="laying_planning_id" value="{{ $laying_planning->id }}">
                <div class="modal-body">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label for="layer_qty">Layer Quantity</label>
                                    <input type="number" class="form-control" id="layer_qty" name="layer_qty" min="0" placeholder="Enter Layer Quantity">
                                </div>
                            </div>
                        </div>
                        <div>
                            <h5>Marker</h5>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="marker_code">Code</label>
                                    <input type="text" class="form-control" id="marker_code" name="marker_code" placeholder="Code">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="marker_yard">Yard</label>
                                    <input type="number" class="form-control" id="marker_yard" name="marker_yard" placeholder="Yard" min="0" step="0.01">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="marker_inch">Inch</label>
                                    <input type="number" class="form-control" id="marker_inch" name="marker_inch" placeholder="Inch" min="0" step="0.01">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="marker_allowence">Allowence</label>
                                    <div class="input-group">
                                        <input type="string" class="form-control" id="marker_allowence" name="marker_allowence" value="1" step="0.01" readonly>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Inch</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="marker_length">Length</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="marker_length" name="marker_length" value="0" readonly>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Yard</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="marker_total_length">Total Length</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="marker_total_length" name="marker_total_length" value="0" readonly>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Yard</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h5>Ratio</h5>
                        </div>
                        <div class="row">
                            @foreach($size_list as $key => $size)
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="ratio_size_{{ $size->id }}">{{ $size->size }}</label>
                                    <input type="number" class="form-control ratio-size" id="ratio_size_{{ $size->id }}" name="ratio_size[{{ $size->id }}]" min="0">
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div>
                            <h5>Qty Each Size</h5>
                        </div>
                        <div class="row">
                            @foreach($size_list as $key => $size)
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="qty_size_{{ $size->id }}">{{ $size->size }}</label>
                                    <input type="number" class="form-control" id="qty_size_{{ $size->id }}" name="qty_size[{{ $size->id }}]" readonly>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="qty_size_all"><h5>Total Qty All Size</h5></label>
                                    <input type="number" class="form-control" id="qty_size_all" name="qty_size_all" value="0" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END .card-body -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btn_submit">Add Cutting Table</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal Duplicate -->
<div class="modal fade" id="modal_duplicate" tabindex="-1" role="dialog" aria-labelledby="modal_duplicateLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_duplicateLabel">Duplicate Cutting Table</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('laying-planning.detail-duplicate') }}" method="POST" class="custom-validation" enctype="multipart/form-data" id="duplicate_form">
                @csrf
                <input type="hidden" name="laying_planning_detail_id" id="laying_planning_detail_id" value="">

                <div class="modal-body">
                    <div class="card-body">
                        <div class="row mb-5">
                            <div class="col-sm-6">
                                <table class="text-left">
                                    <tbody>
                                        <tr>
                                            <td>Table No.</td>
                                            <td class="pl-3">:</td>
                                            <td id="duplicate_table_no"></td>
                                        </tr>
                                        <tr>
                                            <td>Marker Code</td>
                                            <td class="pl-3">:</td>
                                            <td id="duplicate_marker_code"></td>
                                        </tr>
                                        <tr>
                                            <td>Marker Yard</td>
                                            <td class="pl-3">:</td>
                                            <td id="duplicate_marker_yard"></td>
                                        </tr>
                                        <tr>
                                            <td>Marker Inch</td>
                                            <td class="pl-3">:</td>
                                            <td id="duplicate_marker_inch"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-6">
                                <table class="text-left">
                                    <tbody>
                                        <tr>
                                            <td>Layer Qty</td>
                                            <td class="pl-3">:</td>
                                            <td id="duplicate_layer_qty"></td>
                                        </tr>
                                        <tr>
                                            <td>Ratio</td>
                                            <td class="pl-3">:</td>
                                            <td id="duplicate_size_ratio"></td>
                                        </tr>
                                        <tr>
                                            <td>Total Each Size</td>
                                            <td class="pl-3">:</td>
                                            <td id="duplicate_each_size"></td>
                                        </tr>
                                        <tr>
                                            <td>Total Qty All Size</td>
                                            <td class="pl-3">:</td>
                                            <td id="duplicate_total_all_size"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                <label for="duplicate_qty">Duplicate Qty</label>
                                    <input type="number" class="form-control" id="duplicate_qty" name="duplicate_qty" min="0" max="15" placeholder="Enter Duplicate Qty" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END .card-body -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btn_submit_duplicate">Duplicate Cutting Table</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')

<script type="text/javascript">
    //!!  next remove document ready kalau tidak ada masalah
// $(document).ready(function(){

    // ## Show Flash Message
    let session = {!! json_encode(session()->all()) !!};
    show_flash_message(session);

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const size_list = {!! json_encode($size_list) !!};
    const create_url = '{{ route("laying-planning.detail-create") }}';
    const fetch_cutting_table_url = '{{ route("fetch.cutting-table") }}';
    const unprint_cor_url = '{{ route("laying-planning-detail.unprint-cor") }}';
    const unprint_fbr_url = '{{ route("laying-planning-detail.unprint-fbr") }}';

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#duplicate_qty').tooltip({
        title: "Duplikat data maximal 15",
        placement: "top",
        trigger: "focus"
    });

    $('#btn_modal_create').click((e) => {
        $('#modal_formLabel').text("Add Cutting Table")
        $('#btn_submit').text("Add Cutting Table")
        $('#planning_detail_form').find("input[type=text], input[type=number], textarea").val("");
        $('#planning_detail_form').find('input[name="_method"]').remove();
        $('#planning_detail_form').attr('action', create_url);
        $('#modal_form').modal('show')

        set_qty_size(size_list);
        set_marker_length();
        set_marker_total_length();
    });

    $('#layer_qty, #marker_yard, #marker_inch, .ratio-size').on('keyup', function(e) {
        set_marker_length();
        set_marker_total_length();
        set_qty_size(size_list);
    });

    
    $(".btn-detail-edit").on('click', async function(e) {
        let get_data_url = $(this).attr('data-url');
        let planning_detail_id = $(this).attr('data-id');
        let result = await get_using_fetch(get_data_url);
        
        if(result.status == "success"){
            let update_url = '{{ route("laying-planning.detail-update",":id") }}';
            update_url = update_url.replace(':id', planning_detail_id);

            laying_planning_detail_id = $(this).attr('data-id');
            modal_show_edit(update_url, result.data)
            
        } else {
            swal_failed({ title: result.message });

        }
    });

    $('.btn-detail-delete').on('click', async function(e){

        data = { title: "Are you sure?" };
        let confirm_delete = await swal_delete_confirm(data);
        if(!confirm_delete) { return false; };

        let url_delete = $(this).attr('data-url');
        const data_params = {
            token: token
        };

        result = await delete_using_fetch(url_delete, data_params);
        if(result.status == "success"){
            swal_info({
                title : result.message,
                reload_option: true, 
            });
        } else {
            swal_failed({ title: result.message });
        }

    });

    $('#print_multi_fabric').on('click', function(e){
        let fbr_ids = [];
        $('input[name="fbr_ids[]"]:checked').each(function() {
            fbr_ids.push($(this).val());
        });
        if(fbr_ids.length == 0){
            swal_failed({ title: "Please select cutting table" });
            return false;
        }
        if(fbr_ids.length > 25){
            swal_failed({ title: "Maximal 25 request form fabric" });
            return false;
        }
        let url = $(this).attr('href');
        url = url + '?fbr_ids=' + fbr_ids;
        $(this).attr('href', url);
    });

    $('#print_multi_nota').on('click', function(e){
        let laying_planning_laying_planning_detail_ids = [];
        $('input[name="laying_planning_laying_planning_detail_ids[]"]:checked').each(function() {
            laying_planning_laying_planning_detail_ids.push($(this).val());
        });
        if(laying_planning_laying_planning_detail_ids.length == 0){
            swal_failed({ title: "Please select cutting table" });
            return false;
        }
        if(laying_planning_laying_planning_detail_ids.length > 25){
            swal_failed({ title: "Maximal 25 cutting table" });
            return false;
        }
        let url = $(this).attr('href');
        url = url + '?laying_planning_laying_planning_detail_ids=' + laying_planning_laying_planning_detail_ids;
        $(this).attr('href', url);
    });

    $('.btn-detail-duplicate').on('click', async function(e){
        
        $('#duplicate_form').find("input[type=text], input[type=number], textarea").val("");

        laying_planning_detail_id = $(this).attr('data-id');
        let data_params = { laying_planning_detail_id: laying_planning_detail_id };
        cutting_table_result = await using_fetch(fetch_cutting_table_url, data_params, "GET");
        
        data = cutting_table_result.data;
        $('#duplicate_table_no').text(data.table_number);
        $('#duplicate_marker_code').text(data.marker_code);
        $('#duplicate_marker_yard').text(data.marker_yard);
        $('#duplicate_marker_inch').text(data.marker_inch);
        $('#duplicate_layer_qty').text(data.layer_qty);
        $('#duplicate_size_ratio').text(data.size_ratio);
        $('#duplicate_each_size').text(data.each_size);
        $('#duplicate_total_all_size').text(data.total_all_size);
        $('#laying_planning_detail_id').val(data.id);

        $('#modal_duplicate').modal('show');
    });
// })
</script>

<script type="text/javascript">

    function reset_form(data = {}) {
        $('#modal_create_form').text(data.title);
        $('#btn_submit').text(data.btn_text);
        $('#create_form').find("input[type=text], textarea").val("");
        $('#create_form').find('input[name="_method"]').remove();
        $('#modal_form').modal('show');
    };

    const set_marker_length = () => {
        let marker_yard = $('#marker_yard').val() ? parseFloat($('#marker_yard').val()) : 0;
        let marker_inch = $('#marker_inch').val() ? parseFloat($('#marker_inch').val()) : 0;
        let marker_length = (marker_yard + ((marker_inch + parseFloat($('#marker_allowence').val())) / 36)).toFixed(2);
        $('#marker_length').val(marker_length);
        set_marker_total_length();
    };

    const set_marker_total_length = () => {
        let layer_qty = $('#layer_qty').val()? parseFloat($('#layer_qty').val()) : 0;
        let marker_length = $('#marker_length').val() ? parseFloat($('#marker_length').val()) : 0;
        let marker_total_length = (layer_qty * marker_length).toFixed(2);
        $('#marker_total_length').val(marker_total_length);
    };

    const set_qty_size = (size_list) => {
        size_list.forEach(function(size) {
            let size_ratio = $(`#ratio_size_${size.id}`).val() ? parseInt($(`#ratio_size_${size.id}`).val()) : 0;
            $(`#ratio_size_${size.id}`).val(size_ratio)
            let layer_qty = $('#layer_qty').val() ? parseFloat($('#layer_qty').val()) : 0;
            let qty_size = size_ratio * layer_qty;
            $(`#qty_size_${size.id}`).val(qty_size);
        });
        set_qty_all_size(size_list);
    };

    const set_qty_all_size = (size_list) => {
        let total_all_size = 0;
        size_list.forEach(function(size) {
            qty_size = $(`#qty_size_${size.id}`).val() ? parseInt($(`#qty_size_${size.id}`).val()) : 0;
            total_all_size += qty_size;
        });
        $(`#qty_size_all`).val(total_all_size);
    }
</script>

<script type="text/javascript">
    async function delete_using_fetch(url = "", data = {}) {
        const response = await fetch(url, {
            method: "DELETE",
            mode: "cors",
            cache: "no-cache",
            credentials: "same-origin",
            headers: {
                "Content-Type": "application/json",
                'X-CSRF-TOKEN': data.token
            },
            redirect: "follow",
            referrerPolicy: "no-referrer",
        });
        return response.json();
    }

    async function get_using_fetch(url = "", data = {}) {
        const response = await fetch(url, {
            method: "GET",
            mode: "cors",
            cache: "no-cache",
            credentials: "same-origin",
            headers: {
                "Content-Type": "application/json",
            },
            redirect: "follow",
            referrerPolicy: "no-referrer",
        });
        return response.json();
    }

    function modal_show_edit(update_url, data) {
        form = $('#planning_detail_form')
        form.append('<input type="hidden" name="_method" value="PUT">');
        $('#modal_formLabel').text("Edit Cutting Table");
        $('#btn_submit').text("Save");

        form.attr('action', update_url);
        form.find('input[name="layer_qty"]').val(data.layer_qty);
        form.find('input[name="marker_code"]').val(data.marker_code);
        form.find('input[name="marker_yard"]').val(data.marker_yard);
        form.find('input[name="marker_inch"]').val(data.marker_inch);
        
        data.laying_planning_detail_size.forEach(function(detail_size) {
            form.find(`input[name="ratio_size[${detail_size.size_id}]"]`).val(detail_size.ratio_per_size);
            form.find(`input[name="qty_size[${detail_size.size_id}]"]`).val(detail_size.qty_per_size);
        });
        form.find('input[name="marker_length"]').val(parseFloat(data.marker_length).toFixed(2));
        form.find('input[name="marker_total_length"]').val(data.total_length);
        form.find('input[name="qty_size_all"]').val(data.total_all_size);

        $('#modal_form').modal('show')
    }
</script>

<script type="text/javascript">

    // ## Javascript for Checkbox Feature

    const is_all_checked = () => {
        let all_print_checkbox = document.getElementsByClassName('checkbox-print-control');
        if(all_print_checkbox.length <= 0) { return false; }
        for (let item of all_print_checkbox) {
            if(!item.checked) { return false; }
        }
        return true;
    }

    const is_any_checked = () => {
        let all_print_checkbox = document.getElementsByClassName('checkbox-print-control');
        for (let item of all_print_checkbox) {
            if(item.checked) { return true; }
        }
        return false;
    }

    // ## checkbox listener for always update print_checkbox_all
    const checkbox_clicked = () => {
        let checked_status_checkbox_all = is_all_checked() ? true : false;
        document.getElementById('print_checkbox_all').checked = checked_status_checkbox_all;

        let disabled_status_action_wrapper = is_any_checked() ? false : true;
        disabled_action_wrapper(disabled_status_action_wrapper);
    }

    const disabled_action_wrapper = (disabled_status = false) => {
        let action_wrapper = document.getElementsByClassName('action-wrapper').item(0);
        let buttons = action_wrapper.querySelectorAll('button');
        buttons.forEach(function(button) {
            button.disabled = disabled_status;
        });
    }

    const get_selected_item = () => {
        let selected_element = $('.checkbox-print-control:checked').toArray();
        let selected_item_value = [];

        selected_element.forEach(element => {
            selected_item_value.push($(element).val());
        });
        return selected_item_value;
    }

    // ## Checkbox All part
    $('.checkbox-all-control').on('click', function(e) {
        let is_checked = $(this).prop('checked');
        let table = $(this).parents('table');
        table.find('.checkbox-print-control').prop('checked',is_checked);
    })

    $('#print_checkbox_all').on('change', function(e) {
        checkbox_clicked();
    })

</script>

<script>
    const unprint_cor = async () => {
        let selected_item = get_selected_item();

        let params_data = {
            selected_item: selected_item
        }

        if(selected_item.length > 0) {
            let fetch_data = {
                url: unprint_cor_url,
                method: "GET",
                token: token,
                data: params_data,
            }

            result = await using_fetch_v2(fetch_data);

            if(result.status == "success"){
                swal_info({
                    title : result.message,
                    reload_option: true,
                });

            } else {
                swal_failed({ title: result.message });
            }
            
        } else {
            swal_failed({ title: 'Please select at least one item' });
        }
    };


    const unprint_fbr = async () => {
        let selected_item = get_selected_item();

        let params_data = {
            selected_item: selected_item
        }

        if(selected_item.length > 0) {
            let fetch_data = {
                url: unprint_fbr_url,
                method: "GET",
                token: token,
                data: params_data,
            }

            result = await using_fetch_v2(fetch_data);

            if(result.status == "success"){
                swal_info({
                    title : result.message,
                    reload_option: true,
                });

            } else {
                swal_failed({ title: result.message });
            }
            
        } else {
            swal_failed({ title: 'Please select at least one item' });
        }
    };
</script>
@endpush('js')