@extends('layouts.master')

@section('title', 'Create Fabric Requisition')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="content-title text-center mb-5">
                        <h3>Create Fabric Requisition</h3>
                    </div>
                    <div class="my-2 pb-4 h4" style="font-size:20px; font-weight:900">
                        <div>NO: {{ $data->serial_number}}</div>
                        <div>Cutting Table No : {{ $data->table_number }}</div>
                    </div>
                    <!-- START FORM -->
                    <form action="{{ route('fabric-requisition.store') }}" method="POST" class="custom-validation" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="laying_planning_detail_id" value="{{ $data->laying_planning_detail_id }}">
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="gl_number" class="form-label">GL Number</label>
                                    <input type="text" class="form-control" readonly value="{{ $data->gl_number }}" id="gl_number" name="gl_number">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="style" class="form-label">Style</label>
                                    <input type="text" class="form-control" readonly value="{{ $data->style }}" id="style" name="style">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="fabric_po" class="form-label">Fabri PO</label>
                                    <input type="text" class="form-control" readonly value="{{ $data->fabric_po }}" id="fabric_po" name="fabric_po">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="no_laying_sheet" class="form-label">Laying No.</label>
                                    <input type="text" class="form-control" readonly value="{{ $data->no_laying_sheet }}" id="no_laying_sheet" name="no_laying_sheet">
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
                                    <label for="color" class="form-label">Color</label>
                                    <input type="text" class="form-control" readonly value="{{ $data->color }}" id="color" name="color">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="quantity_required" class="form-label">Quantity Required</label>
                                    <input type="text" class="form-control" readonly value="{{ $data->quantity_required }}" id="quantity_required" name="quantity_required">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="quantity_issued" class="form-label">Quantity_issued</label>
                                    <input type="text" class="form-control" readonly value="{{ $data->quantity_issued }}" id="quantity_issued" name="quantity_issued">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="difference" class="form-label">Difference</label>
                                    <input type="text" class="form-control" readonly value="{{ $data->difference }}" id="difference" name="difference">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-10rem">
                            <div class="col-md-12 text-right">
                                <a href="{{ url('/laying-planning',$data->laying_planning_id) }}" class="btn btn-secondary shadow-sm">cancel</a>
                                <button type="submit" class="btn btn-primary waves-effect waves-light shadow-sm">Create Fabric Requisition</button>
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