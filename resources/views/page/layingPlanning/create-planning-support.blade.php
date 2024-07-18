@extends('layouts.master')

@section('title', 'Create Planning Support')

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
                    <form action="{{ url('laying-planning') }}" method="POST" class="custom-validation" enctype="multipart/form-data" id="form_laying_planning">
                        @csrf
                        @method('POST')
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
                                        @if ($styles != null)
                                        @foreach ($styles as $style)
                                        <option value="{{ $style->id }}" {{ $style->id == $layingPlanning->style_id ? 'selected' : '' }}>{{ $style->style }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="style_desc" class="form-label">Description</label>
                                    <textarea class="form-control" name="style_desc" id="style_desc" cols="30" rows="2" disabled>{{ $layingPlanning->style->description ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="buyer" class="form-label">Buyer</label>
                                    <input type="hidden" class="form-control" name="buyer" id="buyer" value="{{ $layingPlanning->gl->buyer->id }}">
                                    <input type="text" class="form-control" name="buyer_name" id="buyer_name" readonly value="{{ $layingPlanning->gl->buyer->name }}">
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="color" class="form-label">Color</label>
                                    <select class="form-control select2" id="color" name="color" style="width: 100%;" data-placeholder="Choose Color">
                                        <option value="">Choose Color</option>
                                        @foreach ($colors as $color)
                                        <option value="{{ $color->id }}" {{ $color->id == $layingPlanning->color->id ? 'selected' : '' }}>{{ $color->color }}</option>
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
                            <div class="col-md-6 col-sm-12 d-none">
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
                                        <input type="text" class="form-control datetimepicker-input" data-target="#delivery_date" name="delivery_date" value="{{ $layingPlanning->delivery_date }}" />
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
                                        <input type="text" class="form-control" data-target="#plan_date" name="plan_date" value="{{ $layingPlanning->plan_date }}" readonly>
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
                        <hr>
                        <h5 style="font-weight:700">Fabric Consumption</h5>
                        <div class="row">
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label for="fabric_cons" class="form-label">Portion</label>
                                    <select class="form-control select2" id="fabric_cons" name="fabric_cons" style="width: 100%;" data-placeholder="Choose Portion">
                                        <option value="">Choose Portion</option>
                                        @foreach ($fabricCons as $fabricCon)
                                        <option value="{{ $fabricCon->id }}" {{ $fabricCon->id == $layingPlanning->fabricCons->id ? 'selected' : '' }}>{{ $fabricCon->description }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-6">
                                <div class="form-group">
                                    <label for="fabric_cons_qty" class="form-label">Quantity Consumed</label>
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control" id="fabric_cons_qty" name="fabric_cons_qty" min="0" value="{{ $layingPlanning->fabric_cons_qty }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Yard</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="fabric_cons_desc" class="form-label">Consumption Description</label>
                                    <textarea class="form-control" name="fabric_cons_desc" id="fabric_cons_desc" cols="30" rows="2">{{ $layingPlanning->fabric_cons_desc }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="fabric_type_desc" class="form-label">Fabric Type Content</label>
                                    <textarea class="form-control" name="fabric_type_desc" id="fabric_type_desc" cols="30" rows="2" readonly>{{ $layingPlanning->fabrictype->description }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 my-3 row">
                            <div class="col-sm-12">
                                <h5 style="font-weight:700">Laying Planning Type</h5>
                            </div>
                            <div class="col-sm-12 col-lg-4">
                                <div class="form-group">
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" id="laying_planning_type_combinasi" name="laying_planning_type" value="2" {{ $layingPlanning->laying_planning_type_id == 2 ? 'checked' : '' }}>
                                        <label for="laying_planning_type_combinasi" class="custom-control-label">COMBINASI</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" id="laying_planning_type_interlining" name="laying_planning_type" value="3" {{ $layingPlanning->laying_planning_type_id == 3 ? 'checked' : '' }}>
                                        <label for="laying_planning_type_interlining" class="custom-control-label">INTERLINING</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8 laying-planning-select-option" style="{{ $layingPlanning->laying_planning_type_id == 1 || $layingPlanning->laying_planning_type_id == null ? 'display: none;' : '' }}">
                                <div class="form-group">
                                    <label for="parent_laying_planning" class="form-label">Planning Parent</label>
                                    <select class="form-control select2" id="parent_laying_planning" name="parent_laying_planning" data-placeholder="Select Planning Parent">
                                        <option value="{{ $layingPlanning->id }}">{{ $layingPlanning->serial_number }}</option>
                                    </select>
                                </div>
                                <small class="text-muted"><i>*Pastikan untuk mereferensi ke laying planning utama (body) yang benar</i></small>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="remark" class="form-label">Remark</label> <i>(Optional)</i>
                                <textarea class="form-control" name="remark" id="remark" cols="30" rows="2">{{ $layingPlanning->remark }}</textarea>
                            </div>
                        </div>

                        <div class="row mt-5">
                            <div class="col-sm-12 col-md-6">
                                <label for="fabric_type" class="form-label">List Size</label>
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
                                    <tfoot class="bg-dark">
                                        <tr>
                                            <th class="text-center">Total</th>
                                            <th class="" id="total_size_qty" colspan="2">: </th>
                                        </tr>
                                    </tfoot>
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
                                <a href="{{ url('/laying-planning') }}" class="btn btn-secondary shadow-sm">Cancel</a>
                                <button type="submit" class="btn btn-primary waves-effect waves-light shadow-sm" id="submit_form">Submit</button>
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
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const url_buyer = `{{ route('fetch.buyer') }}`;
    const url_style = `{{ route('fetch.style') }}`;
    const url_fabric_type = `{{ route('fetch.fabric-type') }}`;


    $(document).ready(function() {
        $('#total_size_qty').html(': ' + sum_size_qty()); // ## update total size

        $('.select2').select2({});

        $('#delivery_date').datetimepicker({
            format: 'DD/MM/yyyy',
            date: moment('{{ $layingPlanning->delivery_date }}').format('YYYY-MM-DD')
        });

        $('#select_size').select2({
            minimumResultsForSearch: Infinity
        })

        $('#select_size').select2({
            templateResult: function(data, container) {
                if (data.id === '') {
                    return data.text;
                }
                var $container = $(
                    "<div class='select2-result-repository clearfix'>" +
                    "<div class='select2-result-repository__title'></div>" +
                    "</div>"
                );
                $container.find(".select2-result-repository__title").text(data.text);
                return $container;
            }
        });

        $('#gl').on('change', function(e) {
            let gl_id = $(this).val();
            let data_params = {
                gl_id
            }

            // ## Dynamic Data Select Style depend on Select GL
            using_fetch(url_style, data_params, "GET").then((result) => {
                $('#style').select2().empty();
                let data = result.data.map(function(item) {
                    return {
                        id: item.id,
                        text: item.style
                    };
                });
                let select_style = $('#style').select2({
                    data
                })
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
            let data_params = {
                id: style_id
            }
            using_fetch(url_style, data_params, "GET").then((result) => {
                $('#style_desc').val(result.data[0].description);
            }).catch((err) => {
                console.log(err);
            });
        })

        // ## Fill Fabric Type Description Box depend on Selected Fabric Type
        $('#fabric_type').on('change', function(e) {
            let fabric_type_id = $(this).val();
            let data_params = {
                id: fabric_type_id
            }
            using_fetch(url_fabric_type, data_params, "GET").then((result) => {
                $('#fabric_type_desc').val(result.data[0].description);
            }).catch((err) => {
                console.log(err);
            });
        });
    });
</script>
<script type="text/javascript">
    let element_html;
    let data_row_count = $('#table_laying_planning_size > tbody tr').length;
    let detached_options = [];

    // ## memeriksa jika di dalam tabel belum ada size yang dipilih
    function is_table_empty_data(table_selector) {
        let data_row = $('#table_laying_planning_size > tbody tr td').length;
        if (data_row <= 1) {
            return true;
        } else {
            return false;
        }
    }

    // ## memeriksa jika input form untuk menambahkan size dan quantitiynya masih kosong atau tidak
    function is_select_size_empty() {
        if (!$('#select_size').val()) {
            swal_warning({
                title: "Please select size"
            })
            return false;
        }

        if (!$('#size_qty').val()) {
            swal_warning({
                title: "Please select size quantity"
            })
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
        var get_size = $("input[name='laying_planning_size_id[]']").map(function() {
            return $(this).val();
        }).get();
        let select_size_value = $('#select_size').val();
        if (get_size.includes(select_size_value)) {
            return true;
        }
        return false;
    }

    // ## menjumlahkan quantity tiap size
    function sum_size_qty() {
        var get_size_qty = $("input[name='laying_planning_size_qty[]']").map(function() {
            return $(this).val();
        }).get();
        const sum = get_size_qty.reduce((tempSum, next_arr) => tempSum + parseInt(next_arr), 0);
        return sum;
    }

    // ## user add size ke table list size
    $('#btn_add_laying_size').on('click', function(e) {
        if (!is_select_size_empty()) { // jika form untuk nambah size ada yang belum di isi, maka aksi gagal
            return false;
        }

        element_html = create_tr_element();
        if (is_table_empty_data()) {
            $('#table_laying_planning_size > tbody').html(element_html);
        } else {
            if (is_size_already_added()) {
                swal_warning({
                    title: "Size already added"
                })
            } else {
                $('#table_laying_planning_size > tbody').append(element_html);
                data_row_count++;
            }
        }

        $('#select_size').val('');
        $('#select_size').trigger('change')
        $('#size_qty').val('');
        $('#total_size_qty').html(': ' + sum_size_qty());
    });

    // ## when user click on remove button
    $('#table_laying_planning_size > tbody').on("click", ".btn-delete-size", function(e) {
        e.preventDefault();

        $(this).parent().parent().remove();
        data_row_count--;

        if (is_table_empty_data()) {
            element_html = `
            <tr>
                <td class="text-center align-middle" colspan="3">No Selected Size</td>
            </tr>`;

            $('#table_laying_planning_size > tbody').html(element_html);
        }

        $('#total_size_qty').html(': ' + sum_size_qty());
    });
</script>

<script type="text/javascript">
    // ## Ketika opsi diganti pada select2, panggil validasi, jika valid pesan error menghilang
    $(".select2").on("change", function() {
        if ($(this).valid()) {
            $(this).removeClass("is-invalid");
            $(this).next(".invalid-feedback").remove();
            $(this).parent().find('.select2-container').removeClass('select2-container--error');
        }
    });

    // ## Form Validation
    let rules = {
        gl: {
            required: true,
        },
        style: {
            required: true,
        },
        color: {
            required: true,
        },
        order_qty: {
            required: true,
        },
        delivery_date: {
            required: true,
        },
        fabric_po: {
            required: true,
        },
        fabric_cons: {
            required: true,
        },
        fabric_type: {
            required: true,
        },
        fabric_cons_qty: {
            required: true,
        },
    };
    let messages = {
        gl: {
            required: "Please choose GL Number",
        },
        style: {
            required: "Please choose Style",
        },
        color: {
            required: "Please choose Color",
        },
        order_qty: {
            required: "Please enter Order Qty",
        },
        delivery_date: {
            required: "Please Select Delivery Date",
        },
        fabric_po: {
            required: "Please Enter Fabric PO",
        },
        fabric_cons: {
            required: "Please Choose Portion",
        },
        fabric_type: {
            required: "Please Enter Fabric Type",
        },
        fabric_cons_qty: {
            required: "Please Enter Fabric Cons Qty",
        },
    };
    let validator = $("#form_laying_planning").validate({
        rules: rules,
        messages: messages,
        errorElement: "span",
        ignore: [],
        errorPlacement: function(error, element) {
            error.addClass("invalid-feedback");
            element.closest(".form-group").append(error);

            // ## khusus untuk select2
            if (element.hasClass('select2-hidden-accessible')) {
                error.insertAfter(element.next('span.select2-container'));
            }

            // validasi error pada select2
            if (!$(element).val()) {
                $(element).parent().find('.select2-container').addClass('select2-container--error');
            }
            // ## ----------------------------------------------------

        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        },
        submitHandler: function(form) {
            if (is_table_empty_data()) {
                swal_warning({
                    title: "No Size Selected!",
                    text: "Please select at least one Size"
                })
                return false;
            }
            form.submit();
        }
    });
</script>
@endpush