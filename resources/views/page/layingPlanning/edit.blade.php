@extends('layouts.master')

@section('title', 'Edit Laying Planning')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert text-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <!-- START FORM -->
                    <form action="{{ route('laying-planning.update', $layingPlanning->id) }}" method="POST" class="custom-validation" enctype="multipart/form-data" id="form_laying_planning">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="laying_planning_id" value="{{ $layingPlanning->id }}">
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="gl" class="form-label">GL</label>
                                    <select class="form-control select2" id="gl" name="gl" style="width: 100%;" data-placeholder="Choose GL">
                                        <option value="">Choose GL</option>
                                        @foreach ($gls as $gl)
                                            <option value="{{ $gl->id }}" {{ $gl->id == $layingPlanning->gl_id ? 'selected' : '' }}>{{ $gl->gl_number }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="style" class="form-label">Style</label>
                                    <select class="form-control select2" id="style" name="style" style="width: 100%;" data-placeholder="Choose Style">
                                        <option value=""> Choose Style</option>
                                        @foreach ($styles as $style)
                                            <option value="{{ $style->id }}" {{ $style->id == $layingPlanning->style->id ? 'selected' : '' }}>{{ $style->style }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="style_desc" class="form-label">Description</label>
                                    <textarea class="form-control" name="style_desc" id="style_desc" cols="30" rows="2" disabled>{{ $layingPlanning->style->description }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="buyer" class="form-label">Buyer</label>
                                    <select class="form-control select2" id="buyer" name="buyer" style="width: 100%;" data-placeholder="Choose Buyer">
                                        <option value="">Choose Buyer</option>
                                        @foreach ($buyers as $buyer)
                                            <option value="{{ $buyer->id }}" {{ $buyer->id == $layingPlanning->buyer->id ? 'selected' : '' }} >{{ $buyer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="color" class="form-label">Color</label>
                                    <select class="form-control select2" id="color" name="color" style="width: 100%;" data-placeholder="Choose Color">
                                        <option value="">Choose Color</option>
                                        @foreach ($colors as $color)
                                            <option value="{{ $color->id }}" {{ $color->id == $layingPlanning->color->id ? 'selected' : '' }} >{{ $color->color }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="order_qty" class="form-label">Order Qty</label>
                                    <input type="number" class="form-control" id="order_qty" name="order_qty" min="0" value="{{ $layingPlanning->order_qty }}">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="total_qty" class="form-label">Total Qty</label>
                                    <input type="number" class="form-control" id="total_qty" name="total_qty" min="0" value="{{ $layingPlanning->order_qty }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="delivery_date" class="form-label">Delivery Date</label>
                                    <div class="input-group date" id="delivery_date" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#delivery_date" name="delivery_date" value="{{ $layingPlanning->delivery_date }}"/>
                                        <div class="input-group-append" data-target="#delivery_date" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="plan_date" class="form-label">Plan Date</label>
                                    <div class="input-group" id="plan_date" data-target-input="nearest">
                                        <input type="text" class="form-control" data-target="#plan_date" name="plan_date" value="{{ $layingPlanning->plan_date }}"readonly>
                                        <div class="input-group-append" data-target="#plan_date" data-toggle="">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="fabric_po" class="form-label">Fabric PO</label>
                                    <input type="text" class="form-control" id="fabric_po" name="fabric_po" value="{{ $layingPlanning->fabric_po }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="fabric_type" class="form-label">Fabric Type</label>
                                    <select class="form-control select2" id="fabric_type" name="fabric_type" style="width: 100%;" data-placeholder="Choose Fabric Type">
                                        <option value="">Choose Fabric Type</option>
                                        @foreach ($fabricTypes as $fabricType)
                                            <option value="{{ $fabricType->id }}" {{ $fabricType->id == $layingPlanning->fabricType->id ? 'selected' : '' }}>{{ $fabricType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label for="fabric_cons" class="form-label">Fabric Consumption</label>
                                    <select class="form-control select2" id="fabric_cons" name="fabric_cons" style="width: 100%;" data-placeholder="Choose Fabric Consumption">
                                        <option value="">Choose Fabric Consumption</option>
                                        @foreach ($fabricCons as $fabricCon)
                                            <option value="{{ $fabricCon->id }}" {{ $fabricCon->id == $layingPlanning->fabricCons->id ? 'selected' : '' }}>{{ $fabricCon->description }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-6">
                                <div class="form-group">
                                    <label for="fabric_cons_qty" class="form-label">qty</label>
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control"  id="fabric_cons_qty" name="fabric_cons_qty" min="0" value="{{ $layingPlanning->fabric_cons_qty }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Yard</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-5">
                            <div class="col-sm-12 col-md-6">
                                <table id="table_laying_planning_size" class="table table-bordered align-middle">
                                    <thead class="thead">
                                        <tr>
                                            <th class="text-center">Size</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-center" width="150">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($layingPlanningSizes as $key => $value)
                                        <tr>
                                            <td class="text-center align-middle">
                                                <input type="hidden" name="laying_planning_size_id[]" value="{{ $value->size->id }}">
                                                {{ $value->size->size }}
                                            </td>
                                            <td class="text-center align-middle">
                                                <input type="hidden" name="laying_planning_size_qty[]" value="{{ $value->quantity }}">
                                                {{ $value->quantity }}
                                            </td>
                                            <td class="text-center align-middle">
                                                <a class="btn btn-sm btn-danger btn-delete-size" data-id="{{ $value->size->id }}">Delete</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-sm-6">
                                <label for="select_size" class="form-label">Add Size</label>
                                <select class="form-control" id="select_size" name="select_size" style="width: 100%;" data-placeholder="Select Size">
                                <option value="">Select Size</option>
                                @foreach ($sizes as $size)
                                    <option value="{{ $size->id }}">{{ $size->size }}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 col-sm-4">
                                <div class="form-group">
                                    <label for="size_qty" class="form-label">Size Qty</label>
                                    <input type="number" class="form-control" id="size_qty" name="size_qty" min="0">
                                </div>
                            </div>
                            <div class="col-md-1 col-sm-2">
                                <div class="form-group">
                                    <label for="" class="" style="color: rgba(255, 255, 255, 0">.</label>
                                    <a id="btn_add_laying_size" class="btn btn-success form-control">Add Size</a>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-10rem">
                            <div class="col-md-12 text-right">
                                <a href="{{ url('/laying-planning') }}" class="btn btn-secondary shadow-sm">cancel</a>
                                <a type="button" class="btn btn-primary waves-effect waves-light shadow-sm" id="submit_form">Submit</a>
                            </div>
                        </div>
                    </form>
                    <!-- END FORM -->
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('js')
<script type="text/javascript">
    $( document ).ready(function() {
        $('.select2').select2({ 
        
        });

        $('#delivery_date').datetimepicker({
            format: 'DD/MM/yyyy',
            date: moment('{{ $layingPlanning->delivery_date }}').format('YYYY-MM-DD')
        });
    
        $('#select_size').select2({
            minimumResultsForSearch: Infinity
        })

        $('#gl').on('change', function(e) {
            let gl_id = $(this).val();

            let params = new URLSearchParams();
            params.append('gl_id', gl_id);
            let url_gl = `{{ url('ajax/get-style') }}?${params.toString()}`;

            get_data_using_fetch(url_gl).then((result) => {
                $('#style').select2().empty();
                var data = result.map(function(item) {
                    return {
                        id: item.id,
                        text: item.style
                    };
                });
                let select_style = $('#style').select2({
                    data: data,
                })
                select_style.trigger('change');
            });
        })

        $('#style').on('change', function(e) {
            let style_id = $(this).val();

            let params = new URLSearchParams();
            params.append('id', style_id);
            url = `{{ url('ajax/get-style') }}?${params.toString()}`;

            get_data_using_fetch(url).then((data) => {
                $('#style_desc').val(data.description);
            });
        })

        $( "#submit_form" ).click(function(e) {
            e.preventDefault();
            if(is_table_empty_data()){
                alert("belum ada size dipilih")
                return false;
            }
            $("#form_laying_planning" ).submit();
        });

    });

    
</script>
<script type="text/javascript">
    let element_html;
    let data_row_count = $('#table_laying_planning_size > tbody tr').length;
    let detached_options = [];

    // memeriksa jika di dalam tabel belum ada size yang dipilih
    function is_table_empty_data(table_selector){ 

        let data_row = $('#table_laying_planning_size > tbody tr td').length;
        if(data_row <= 1){
            return true;
        } else {
            return false;
        }
    }

    // memeriksa jika input form untuk menambahkan size dan quantitiynya masih kosong apa tidak
    function is_select_size_empty(){
        if(!$('#select_size').val()) {
            alert("Please select size")
            return false;
        }
        
        if(!$('#size_qty').val()) {
            alert("Please select size quantity")
            return false;
        }

        return true;
    }

    // membuat baris baru untuk setiap size yang telah di pilih
    function create_tr_element() {
        let select_size_value = $('#select_size').val();
        let select_size_text = $('#select_size option:selected').text();
        let size_qty = $('#size_qty').val();
        let element = `
        <tr>
            <td class="text-center align-middle">
                <input type="hidden" name="laying_planning_size_id[]" value="${select_size_value}">
                ${select_size_text}
            </td>
            <td class="text-center align-middle">
                <input type="hidden" name="laying_planning_size_qty[]" value="${size_qty}">
                ${size_qty}
            </td>
            <td class="text-center align-middle">
                <a class="btn btn-sm btn-danger btn-delete-size" data-id="${select_size_value}">Delete</a>
            </td>
        </tr>`
        return element;
    }

    // memeriksa apakah size yang akan ditambahkan sudah ada di dalam tabel
    function is_size_already_added() {
        var get_size = $("input[name='laying_planning_size_id[]']").map(function(){return $(this).val();}).get();
        let select_size_value = $('#select_size').val();
        if(get_size.includes(select_size_value)){
            return true;
        }
        return false;
    }

    $('#btn_add_laying_size').on('click', function(e) {
        if(!is_select_size_empty()){ // jika form untuk nambah size ada yang belum di isi, maka aksi gagal
            return false;
        }
        
        element_html = create_tr_element();
        if(is_table_empty_data()){
            $('#table_laying_planning_size > tbody').html(element_html);
        } else {
            if(is_size_already_added()){
                alert("Size sudah ditambahkan")
            } else {
                $('#table_laying_planning_size > tbody').append(element_html);
                data_row_count++;
            }
        }

        $('#select_size').val('');
        $('#select_size').trigger('change')
        $('#size_qty').val('');
    });

    //when user click on remove button
    $('#table_laying_planning_size > tbody').on("click",".btn-delete-size", function(e){ 
        e.preventDefault();
        
        $(this).parent().parent().remove();
		data_row_count--;
        
        if(is_table_empty_data()){
            element_html = `
            <tr>
                <td class="text-center align-middle" colspan="3">No Selected Size</td>
            </tr>`;

            $('#table_laying_planning_size > tbody').html(element_html);
        }
    });

</script>

<script type="text/javascript">
    async function get_data_using_fetch(url = "", data = {}) {
    // Default options are marked with *
        const response = await fetch(url, {
            method: "GET", // *GET, POST, PUT, DELETE, etc.
            mode: "cors", // no-cors, *cors, same-origin
            cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
            credentials: "same-origin", // include, *same-origin, omit
            headers: {
                "Content-Type": "application/json",
                // 'Content-Type': 'application/x-www-form-urlencoded',
            },
            redirect: "follow", // manual, *follow, error
            referrerPolicy: "no-referrer", // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
            // body: JSON.stringify(data), // body data type must match "Content-Type" header
        });
        return response.json(); // parses JSON response into native JavaScript objects
    }
</script>
@endpush