@extends('layouts.master')

@section('title', 'Laying Planning Detail')

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
                                            <td>{{ $data->serial_number }}</td>
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
                                            <td>{{ $data->gl->gl_number }}</td>
                                        </tr>
                                        <tr>
                                            <td>Buyer</td>
                                            <td class="pl-3">:</td>
                                            <td>{{ $data->buyer->name }}</td>
                                        </tr>
                                        <tr>
                                            <td>Style</td>
                                            <td class="pl-3">:</td>
                                            <td>{{ $data->style->style }}</td>
                                        </tr>
                                        <tr>
                                            <td>Color</td>
                                            <td class="pl-3">:</td>
                                            <td>{{ $data->color->color }}</td>
                                        </tr>
                                        <tr>
                                            <td>Order Qty</td>
                                            <td class="pl-3">:</td>
                                            <td>{{ $data->order_qty }} Pcs</td>
                                        </tr>
                                        <tr>
                                            <td>Total Qty</td>
                                            <td class="pl-3">:</td>
                                            <td>{{ $data->total_order_qty }} Pcs</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-7">
                                <table>
                                    <tbody class="align-top">
                                        <tr>
                                            <td>Fabric P/O</td>
                                            <td class="pl-3">:</td>
                                            <td>{{ $data->fabric_po }}</td>
                                        </tr>
                                        <tr>
                                            <td>Fabric Type</td>
                                            <td class="pl-3">:</td>
                                            <td>{{ $data->fabricType->description }}</td>
                                        </tr>
                                        <tr>
                                            <td>Fabric Consumpition</td>
                                            <td class="pl-3">:</td>
                                            <td>{{ $data->fabricCons->description }}</td>
                                        </tr>
                                        <tr>
                                            <td>Description</td>
                                            <td class="pl-3">:</td>
                                            <td>{{ $data->style->description }}</td>
                                        </tr>
                                        <tr>
                                            <td>Delivery Date</td>
                                            <td class="pl-3">:</td>
                                            <td>{{ $data->delivery_date }}</td>
                                        </tr>
                                        <tr>
                                            <td>Plan Date</td>
                                            <td class="pl-3">:</td>
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
                                <th scope="col">No. </th>
                                <th scope="col">No Laying Sheet</th>
                                <th scope="col">Total Qty</th>
                                <th scope="col">Marker Length</th>
                                <th scope="col">Layer Qty</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($details as $detail)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $detail->no_laying_sheet }}</td>
                                    <td>{{ $detail->total_length }}</td>
                                    <td>{{ $detail->marker_length }}</td>
                                    <td>{{ $detail->layer_qty }}</td>
                                    <td>
                                        <a href="javascript:void(0);" class="btn btn-primary btn-sm btn-detail-edit" data-id="{{ $detail->id }}" data-url="{{ route('laying-planning.detail-edit', $detail->id) }}">Edit</a>
                                        <a href="javascript:void(0);" class="btn btn-danger btn-sm btn-detail-delete" data-id="{{ $detail->id }}" data-url="{{ route('laying-planning.detail-delete', $detail->id) }}" >Delete</a>
                                        <a href="{{ route('cutting-order.createNota', $detail->id) }}" class="btn btn-info btn-sm {{ $detail->cor_status }}">Create COR</a>
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
                <h5 class="modal-title" id="modal_formLabel">Add Cutting Table</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('laying-planning.detail-create') }}" method="POST" class="custom-validation" enctype="multipart/form-data" id="planning_detail_form">
                @csrf
                <input type="hidden" name="laying_planning_id" value="{{ $data->id }}">
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
                                    <input type="number" class="form-control" id="marker_yard" name="marker_yard" placeholder="Yard" min="0" step="0.01">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="marker_inch">Inch</label>
                                    <input type="number" class="form-control" id="marker_inch" name="marker_inch" placeholder="Inch" min="0" step="0.01">
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
                            <h4>Ratio</h4>
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
                            <h4>Qty Each Size</h4>
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
                            
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="qty_size_all">Total All Size</label>
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
@endsection

@push('js')

<script type="text/javascript">
$(document).ready(function(){

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const size_list = {!! json_encode($size_list) !!};
    const create_url = '{{ route("laying-planning.detail-create") }}';

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
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
            console.log(result.message);
            alert("Terjadi Kesalahan");
        }
    });

    $('.btn-detail-delete').on('click', async function(e){

        if(!confirm("Apakah anda yakin ingin menghapus detail laying planning ini?")) {
            return false;
        }

        let url_delete = $(this).attr('data-url');
        const data_params = {
            token: token
        };

        result = await delete_using_fetch(url_delete, data_params);
        if(result.status == "success"){
            alert(result.message)
            laying_planning_detail_id = $(this).attr('data-id');
            location.reload();
        } else {
            console.log(result.message);
            alert("Terjadi Kesalahan");
        }

    });
})
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
        let marker_length = marker_yard + (marker_inch/36) + 0.04;
        $('#marker_length').val(marker_length);
        set_marker_total_length();
    };

    const set_marker_total_length = () => {
        let layer_qty = $('#layer_qty').val()? parseFloat($('#layer_qty').val()) : 0;
        let marker_length = $('#marker_length').val() ? parseFloat($('#marker_length').val()) : 0;
        let marker_total_length = layer_qty * marker_length;
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
        form.find('input[name="marker_length"]').val(data.marker_length);
        form.find('input[name="marker_total_length"]').val(data.total_length);
        form.find('input[name="qty_size_all"]').val(data.total_all_size);

        $('#modal_form').modal('show')
    }
</script>
@endpush('js')