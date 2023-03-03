@extends('layouts.master')

@section('title', 'Create Laying Planning')

@section('content')
<div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ url('/laying-planning') }}" method="POST" class="custom-validation" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="gl" class="form-label">GL</label>
                                    <select class="form-control" id="gl" name="gl">
                                        @foreach ($gl as $g)
                                            <option value="{{ $g->id }}">{{ $g->gl_number }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="buyer" class="form-label">Buyer</label>
                                    <select class="form-control" id="buyer" name="buyer">
                                        @foreach ($buyer as $b)
                                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="style" class="form-label">Style</label>
                                    <select class="form-control" id="style" name="style">
                                        @foreach ($style as $s)
                                            <option value="{{ $s->id }}">{{ $s->style }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="color" class="form-label">Color</label>
                                    <select class="form-control" id="color" name="color">
                                        @foreach ($color as $c)
                                            <option value="{{ $c->id }}">{{ $c->color }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="laying_planning_date" class="form-label">Laying Planning Date</label>
                                    <input type="date" class="form-control" id="laying_planning_date" name="laying_planning_date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fabric_type" class="form-label">Fabric Type</label>
                                    <select class="form-control" id="fabric_type" name="fabric_type">
                                        @foreach ($fabricType as $ft)
                                            <option value="{{ $ft->id }}">{{ $ft->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="laying_planning_qty" class="form-label">Laying Planning Qty</label>
                                    <input type="number" class="form-control" id="laying_planning_qty" name="laying_planning_qty" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="laying_planning_remark" class="form-label">Laying Planning Remark</label>
                                    <input type="text" class="form-control" id="laying_planning_remark" name="laying_planning_remark" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
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
                                                        @foreach ($size as $s)
                                                            <option value="{{ $s->id }}">{{ $s->size }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" id="qty" name="qty[]" required>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-primary waves-effect waves-light" id="addLayingPlanningSize">Add</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- end table size line -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection