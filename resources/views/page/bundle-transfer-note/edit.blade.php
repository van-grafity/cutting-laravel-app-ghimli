@extends('layouts.master')

@section('title', 'Create Cutting Ticket')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- START FORM -->
                    <form action="{{ route('bundle-transfer-note.update', $data["transfer_note"]->transfer_note_id) }}" method="POST" class="custom-validation" enctype="multipart/form-data" id="form_generate_ticket">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="serial_number" class="form-label">Serial Number</label>
                                    <input type="text" value="{{$data['transfer_note']->serial_number}}" class="form-control" readonly id="serial_number" name="serial_number">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="gl_number" class="form-label">GL Number</label>
                                    <input type="text" value="{{$data['transfer_note']->gl_number}}" class="form-control" readonly id="gl_number" name="gl_number">
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="color" class="form-label">Color</label>
                                    <input type="text" value="{{$data['transfer_note']->color}}" class="form-control" readonly id="color" name="color">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="text" value="{{$data['transfer_note']->date}}" class="form-control" readonly id="date" name="date">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="location" class="form-label">Location</label>
                                    <select class="form-control" id="location" name="location" style="width: 100%;"
                                    data-placeholder="Choose Location">
                                        @foreach ($data["location"] as $locations)
                                            <option value="{{ $locations->id }}" {{ $locations->location == $data['transfer_note']->location? 'selected' : '' }}>{{ $locations->location }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="total_pcs" class="form-label">Total Pcs</label>
                                    <input value="{{$data['total_pcs']}}" class="form-control" name="total_pcs" id="total_pcs" cols="30" rows="2" readonly></input>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-5">
                            <div class="col-md-12 text-right">
                                <a href="{{ url('/bundle-transfer-note') }}" class="btn btn-secondary shadow-sm">cancel</a>
                                <button type="submit" class="btn btn-primary shadow-sm" id="submit_form">Generate Ticket</button>
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
