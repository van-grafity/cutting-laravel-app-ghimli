@extends('layouts.master')

@section('title', 'Create Laying Planning')

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
                    <form action="{{ url('/laying-planning') }}" method="POST" class="custom-validation" enctype="multipart/form-data" id="form_laying_planning">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="gl" class="form-label">GL</label>
                                    <select class="form-control select2" id="gl" name="gl" style="width: 100%;" data-placeholder="Choose GL">
                                        <option value="">Choose GL</option>
                                        @foreach ($gls as $gl)
                                            <option value="{{ $gl->id }}">{{ $gl->gl_number }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="style" class="form-label">Style</label>
                                    <select class="form-control select2" id="style" name="style" style="width: 100%;" data-placeholder="Choose GL First">
                                        <option value=""> Choose GL First</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="style_desc" class="form-label">Description</label>
                                    <textarea class="form-control" name="style_desc" id="style_desc" cols="30" rows="2" disabled></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="buyer" class="form-label">Buyer</label>
                                    <input type="hidden" class="form-control" name="buyer" id="buyer" readonly>
                                    <input type="text" class="form-control" name="buyer_name" id="buyer_name" readonly>
                                </div>
                            </div>
                            
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="color" class="form-label">Color</label>
                                    <select class="form-control select2" id="color" name="color" style="width: 100%;" data-placeholder="Choose Color">
                                        <option value="">Choose Color</option>
                                        @foreach ($colors as $color)
                                            <option value="{{ $color->id }}">{{ $color->color }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="order_qty" class="form-label">Order Qty</label>
                                    <input type="number" class="form-control" id="order_qty" name="order_qty" min="0">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="total_qty" class="form-label">Total Qty</label>
                                    <input type="number" class="form-control" id="total_qty" name="total_qty" min="0">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="delivery_date" class="form-label">Delivery Date</label>
                                    <div class="input-group date" id="delivery_date" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#delivery_date" name="delivery_date"/>
                                        <div class="input-group-append" data-target="#delivery_date" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="plan_date" class="form-label">Plan Date</label>
                                    <div class="input-group date">
                                        <input type="text" class="form-control" name="plan_date" id="plan_date" readonly>
                                        <div class="input-group-append">
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
                                    <input type="text" class="form-control" id="fabric_po" name="fabric_po">
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
                                            <option value="{{ $fabricType->id }}">{{ $fabricType->name }}</option>
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
                                            <option value="{{ $fabricCon->id }}">{{ $fabricCon->description }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-6">
                                <div class="form-group">
                                    <label for="fabric_cons_qty" class="form-label">qty</label>
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control"  id="fabric_cons_qty" name="fabric_cons_qty" min="0">
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
                                        <tr>
                                            <td class="text-center align-middle" colspan="3">No Selected Size</td>
                                        </tr>
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
    const url_buyer = `{{ route('fetch.buyer') }}`;
    const url_style = `{{ route('fetch.style') }}`;
    
    $( document ).ready(function() {
        $('.select2').select2({ 
        
        });
        $('#delivery_date').datetimepicker({
            format: 'DD/MM/yyyy',
        });
        $('#plan_date').val(moment().format('DD/MM/yyyy'))
    
        $('#select_size').select2({
            minimumResultsForSearch: Infinity
        })

        $('#gl').on('change', function(e) {
            let gl_id = $(this).val();
            let data_params = { gl_id }

            // ## Dynamic Data Select Style depend on Select GL
            using_fetch(url_style, data_params, "GET").then((result) => {
                $('#style').select2().empty();
                let data = result.data.map(function(item) {
                    return {
                        id: item.id,
                        text: item.style
                    };
                });
                let select_style = $('#style').select2({ data })
                select_style.trigger('change');
            }).catch((err) => {
                console.log(err);
            });

            // ## Dynamic Data Buyer depend on Select GL
            using_fetch(url_buyer, data_params, "GET").then((result) => {
                $('#buyer').val(result.data[0].id);
                $('#buyer_name').val(result.data[0].name);
            }).catch((err) => {
                console.log(err);
            });
        })

        // ## Fill Style Description Box depend on Selected Style
        $('#style').on('change', function(e) {
            let style_id = $(this).val();
            let data_params = { id : style_id }
            using_fetch(url_style, data_params, "GET").then((result) => {
                $('#style_desc').val(result.data[0].description);
            }).catch((err) => {
                console.log(err);
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

    // ## memeriksa jika di dalam tabel belum ada size yang dipilih
    function is_table_empty_data(table_selector){ 

        let data_row = $('#table_laying_planning_size > tbody tr td').length;
        if(data_row <= 1){
            return true;
        } else {
            return false;
        }
    }

    // ## memeriksa jika input form untuk menambahkan size dan quantitiynya masih kosong apa tidak
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

    // ## membuat baris baru untuk setiap size yang telah di pilih
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

    // ## memeriksa apakah size yang akan ditambahkan sudah ada di dalam tabel
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

        // let select_size_value = $('#select_size').val();
        // detach_element = $(`#select_size option[value='${select_size_value}'`).detach();
        // let detach_option = {
        //     'value': detach_element[0].value,
        //     'text': detach_element[0].text,
        // };
        // detached_options.push(detach_option);
        // console.log(detached_options);


        $('#select_size').val('');
        $('#select_size').trigger('change')
        $('#size_qty').val('');
    });


    // ## when user click on remove button
    $('#table_laying_planning_size > tbody').on("click",".btn-delete-size", function(e){ 
        e.preventDefault();

        // deleted_size_id = $(this).data('id');
        // insert_option_after_delete(deleted_size_id);
        
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

    // function insert_option_after_delete(option_value){
    //     var result = detached_options.filter(obj => {
    //         return obj.value === `${option_value}`
    //     })
    //     result.forEach( data => {
    //         let new_option = new Option(data.text, data.value, false, false);
    //         console.log(new_option);
    //         $('#select_size').append(new_option).trigger('change');
    //     })
    // }

</script>
@endpush