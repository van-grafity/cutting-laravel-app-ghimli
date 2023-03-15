@extends('layouts.master')

@section('title', 'Create Cutting Order Record')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="my-2 pb-4 h4" style="font-size:20px; font-weight:900">
                        <div>NO: {{ $data->no_laying_sheet}}</div>
                        <div>Cutting Table No : {{ $data->table_number }}</div>
                    </div>
                    <!-- START FORM -->
                    <form action="{{ url('/cutting-order') }}" method="POST" class="custom-validation" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="gl_number" class="form-label">GL Number</label>
                                    <input type="text" class="form-control" readonly value="{{ $data->gl_number }}" id="gl_number" name="gl_number">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="style" class="form-label">Style</label>
                                    <input type="text" class="form-control" readonly value="{{ $data->style }}" id="style" name="style">
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="style_desc" class="form-label">Description</label>
                                    <textarea class="form-control" name="style_desc" id="style_desc" cols="30" rows="2" disabled>{{ $data->style_desc }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="buyer" class="form-label">Buyer</label>
                                    <input type="text" class="form-control" readonly value="{{ $data->buyer }}" id="buyer" name="buyer">
                                </div>
                            </div>
                            
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="color" class="form-label">Color</label>
                                    <input type="text" class="form-control" readonly value="{{ $data->color }}" id="color" name="color">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="layer" class="form-label">Layer</label>
                                    <input type="text" class="form-control" readonly value="{{ $data->layer }}" id="layer" name="layer">
                                </div>
                            </div>
                            
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="fabric_po" class="form-label">Fabri PO</label>
                                    <input type="text" class="form-control" readonly value="{{ $data->fabric_po }}" id="fabric_po" name="fabric_po">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="fabric_type" class="form-label">Fabric Type</label>
                                    <textarea class="form-control" name="fabric_type" id="fabric_type" cols="30" rows="2" readonly>{{ $data->fabric_type }}</textarea>
                                </div>
                            </div>
                            
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="fabric_cons" class="form-label">Fabric Consumption</label>
                                    <textarea class="form-control" name="fabric_cons" id="fabric_cons" cols="30" rows="2" readonly>{{ $data->fabric_consumption }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="length" class="form-label">Marker Length</label>
                                    <input type="text" class="form-control" readonly value='{{ $data->marker_length }}"' id="length" name="length">
                                </div>
                            </div>
                            
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="size_ratio" class="form-label">Size Ratio</label>
                                    <textarea class="form-control" name="size_ratio" id="size_ratio" cols="30" rows="2" readonly> {{ $data->size_ratio }} </textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-10rem">
                            <div class="col-md-12 text-right">
                                <a href="{{ url('/laying-planning',$data->id) }}" class="btn btn-secondary shadow-sm">cancel</a>
                                <a href="{{ url('/cutting-order') }}" class="btn btn-primary shadow-sm">Create Cutting Order</a>
                                <!-- <button type="submit" class="btn btn-primary waves-effect waves-light shadow-sm">Create Cutting Order</button> -->
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

</script>
@endpush