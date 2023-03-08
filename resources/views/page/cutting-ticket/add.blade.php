@extends('layouts.master')

@section('title', 'Create Cutting Ticket')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- START FORM -->
                    <form action="{{ url('/cutting-order') }}" method="POST" class="custom-validation" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="select_laying_sheet" class="form-label">No. Laying Sheet</label>
                                        <select class="form-control" id="select_laying_sheet" name="select_laying_sheet" style="width: 100%;" data-placeholder="Choose Laying Sheet">
                                        <option value="">Choose Laying Sheet</option>
                                        <option value="1">62843-001</option>
                                        <option value="2">62843-002</option>
                                        <option value="3">62843-003</option>
                                        <option value="4">62843-004</option>
                                        <option value="26" selected>62843-026</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="gl_number" class="form-label">GL Number</label>
                                    <input type="text" class="form-control" readonly value="62843-00" id="gl_number" name="gl_number">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="cutting_lot" class="form-label">Cutting Lot</label>
                                    <input type="text" class="form-control" readonly value="26" id="cutting_lot" name="cutting_lot">
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
                                    <label for="color" class="form-label">Color</label>
                                    <input type="text" class="form-control" readonly value="MED HEATHER GREY H12R (053)" id="color" name="color">
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
                                    <label for="size_ratio" class="form-label">Size Ratio</label>
                                    <textarea class="form-control" name="size_ratio" id="size_ratio" cols="30" rows="2" readonly> XS = 3 | M = 1 | L = 4 | XL = 5 </textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-10rem">
                            <div class="col-md-12 text-right">
                                <a href="{{ url('/cutting-ticket') }}" class="btn btn-secondary shadow-sm">cancel</a>
                                <a href="{{ url('/cutting-ticket') }}" class="btn btn-primary shadow-sm">Generate Ticket</a>
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
    $('#select_laying_sheet').select2({
            minimumResultsForSearch: Infinity
        })
</script>
@endpush