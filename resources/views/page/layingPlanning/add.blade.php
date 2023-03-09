@extends('layouts.master')

@section('title', 'Create Laying Planning')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- START FORM -->
                    <form action="{{ url('/laying-planning') }}" method="POST" class="custom-validation" enctype="multipart/form-data">
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
                                    <select class="form-control select2" id="style" name="style"   style="width: 100%;" data-placeholder="Choose Style">
                                        <option value=""> Choose Style</option>
                                        @foreach ($styles as $style)
                                            <option value="{{ $style->id }}">{{ $style->style }}</option>
                                        @endforeach
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
                                    <select class="form-control select2" id="buyer" name="buyer" style="width: 100%;" data-placeholder="Choose Buyer">
                                        <option value="">Choose Buyer</option>
                                        @foreach ($buyers as $buyer)
                                            <option value="{{ $buyer->id }}">{{ $buyer->name }}</option>
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
                                    <div class="input-group date" id="plan_date" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#plan_date" name="plan_date" readonly>
                                        <div class="input-group-append" data-target="#plan_date" data-toggle="datetimepicker">
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
                            <div class="col-md-3 col-sm-8">
                                <!-- <h3>Add Size</h3> -->
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
                            <div class="col-md-1 col-sm-4">
                                <div class="form-group">
                                    <label for="" class="" style="color: rgba(255, 255, 255, 0">.</label>
                                    <button class="btn btn-success form-control">Add Size</button>
                                </div>
                            </div>
                        </div>


                        <div class="row d-none">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Size</th>
                                                <th>Qty</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableLayingPlanningSize">
                                            <tr>
                                                <td>
                                                    <select class="form-control" id="size" name="size[]">
                                                        @foreach ($sizes as $size)
                                                            <option value="{{ $size->id }}">{{ $size->size }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" id="qty" name="qty[]">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-primary waves-effect waves-light" id="addLayingPlanningSize">Add</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <table class="table table-bordered table-striped col-md-6">
                            <thead>
                                <tr>
                                    <th class="text-center">Size</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tableLayingPlanningSize">
                                <tr>
                                    <!-- add td center -->
                                    <td class="text-center">
                                        XS
                                    </td>
                                    <td class="text-center">
                                        240
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a class="btn btn-primary" href="'.route('laying-planning.edit', $colors->id).'">Edit</a>
                                            <a class="btn btn-danger">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">
                                        S
                                    </td>
                                    <td class="text-center">
                                        0
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a class="btn btn-primary" href="'.route('laying-planning.edit', $colors->id).'">Edit</a>
                                            <a class="btn btn-danger">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">
                                        M
                                    </td>
                                    <td class="text-center">
                                        80
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a class="btn btn-primary" href="'.route('laying-planning.edit', $colors->id).'">Edit</a>
                                            <a class="btn btn-danger">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">
                                        L
                                    </td>
                                    <td class="text-center">
                                        320
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a class="btn btn-primary" href="'.route('laying-planning.edit', $colors->id).'">Edit</a>
                                            <a class="btn btn-danger">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                <td class="text-center">
                                        XL
                                    </td>
                                    <td class="text-center">
                                        400
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a class="btn btn-primary" href="'.route('laying-planning.edit', $colors->id).'">Edit</a>
                                            <a class="btn btn-danger">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                <td class="text-center">
                                        XXL
                                    </td>
                                    <td class="text-center">
                                        0
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a class="btn btn-primary" href="'.route('laying-planning.edit', $colors->id).'">Edit</a>
                                            <a class="btn btn-danger">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="row mt-10rem">
                            <div class="col-md-12 text-right">
                                <a href="{{ url('/laying-planning') }}" class="btn btn-secondary shadow-sm">cancel</a>
                                <button type="submit" class="btn btn-primary waves-effect waves-light shadow-sm">Submit</button>
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
        console.log( "ready!" );
        $('.select2').select2({
        
        });
        $('#delivery_date').datetimepicker({
            format: 'L',
        });
        $('#plan_date').datetimepicker({
            format: 'L',
            defaultDate: moment().format('YYYY-MM-DD'),
        });
    
        $('#select_size').select2({
            minimumResultsForSearch: Infinity
        })
    });

</script>
@endpush