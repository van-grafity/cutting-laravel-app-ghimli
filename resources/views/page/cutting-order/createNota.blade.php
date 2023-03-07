@extends('layouts.master')

@section('title', 'Create Cutting Order Record')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="my-2 pb-4 h4" style="font-size:20px; font-weight:900">
                        <div>NO: 62843-026</div>
                        <div>Cutting Table No : 26</div>
                    </div>
                    <!-- START FORM -->
                    <form action="{{ url('/cutting-order') }}" method="POST" class="custom-validation" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="gl_number" class="form-label">GL Number</label>
                                    <input type="text" class="form-control" readonly value="62843-00" id="gl_number" name="gl_number">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="style" class="form-label">Style</label>
                                    <input type="text" class="form-control" readonly value="5243AU22" id="style" name="style">
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="style_desc" class="form-label">Description</label>
                                    <textarea class="form-control" name="style_desc" id="style_desc" cols="30" rows="2" disabled>Short Sleeve Polos</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="buyer" class="form-label">Buyer</label>
                                    <input type="text" class="form-control" readonly value="AEROPOSTALE" id="buyer" name="buyer">
                                </div>
                            </div>
                            
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="color" class="form-label">Color</label>
                                    <input type="text" class="form-control" readonly value="MED HEATHER GREY H12R (053)" id="color" name="color">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="layer" class="form-label">Layer</label>
                                    <input type="text" class="form-control" readonly value="80" id="layer" name="layer">
                                </div>
                            </div>
                            
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="fabric_po" class="form-label">Fabri PO</label>
                                    <input type="text" class="form-control" readonly value="100048963" id="fabric_po" name="fabric_po">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="fabric_type" class="form-label">Fabric Type</label>
                                    <textarea class="form-control" name="fabric_type" id="fabric_type" cols="30" rows="2" readonly>57% cotton 38 polyester 5% spandex pique 185gm/m</textarea>
                                </div>
                            </div>
                            
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="fabric_cons" class="form-label">Fabric Consumption</label>
                                    <textarea class="form-control" name="fabric_cons" id="fabric_cons" cols="30" rows="2" readonly>BODY+Sleeves+top and under placket :5.62yds x 74" x 322gm (cuttable)- Ctn poly spandex pique</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="length" class="form-label">Marker Length</label>
                                    <input type="text" class="form-control" readonly value='5yd 35" 12"' id="length" name="length">
                                </div>
                            </div>
                            
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="size_ratio" class="form-label">Size Ratio</label>
                                    <textarea class="form-control" name="size_ratio" id="size_ratio" cols="30" rows="2" readonly> XS = 3 | M = 1 | L = 4 | XL = 5 </textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-10rem">
                            <div class="col-md-12 text-right">
                                <a href="{{ url('/laying-planning',1) }}" class="btn btn-secondary shadow-sm">cancel</a>
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