@extends('layouts.master')

@section('title', 'Create Cutting Ticket')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- START FORM -->
                    <form action="{{ route('cutting-ticket.generate') }}" method="POST" class="custom-validation" enctype="multipart/form-data" id="form_generate_ticket">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="laying_planning_detail_id" class="form-label">No. Laying Sheet</label>
                                        <select class="form-control" id="laying_planning_detail_id" name="laying_planning_detail_id" style="width: 100%;" data-placeholder="Choose Laying Sheet">
                                        <option value="">Choose Laying Sheet</option>
                                        @foreach ( $no_laying_sheet_list as $laying_sheet )
                                            <option value="{{ $laying_sheet->id }}">{{ $laying_sheet->no_laying_sheet }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="gl_number" class="form-label">GL Number</label>
                                    <input type="text" class="form-control" readonly id="gl_number" name="gl_number">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="table_number" class="form-label">Table No</label>
                                    <input type="text" class="form-control" readonly id="table_number" name="table_number">
                                </div>
                            </div>
                            
                        </div>

                        <div class="row">
                            
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="style" class="form-label">Style</label>
                                    <input type="text" class="form-control" readonly id="style" name="style">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="color" class="form-label">Color</label>
                                    <input type="text" class="form-control" readonly id="color" name="color">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="buyer" class="form-label">Buyer</label>
                                    <input type="text" class="form-control" readonly id="buyer" name="buyer">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="size_ratio" class="form-label">Size Ratio</label>
                                    <textarea class="form-control" name="size_ratio" id="size_ratio" cols="30" rows="2" readonly></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-10rem">
                            <div class="col-md-12 text-right">
                                <a href="{{ url('/cutting-ticket') }}" class="btn btn-secondary shadow-sm">cancel</a>
                                <a href="{{ url('/cutting-ticket') }}" class="btn btn-primary shadow-sm" id="submit_form">Generate Ticket</a>
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
    
    const url_get_laying_sheet = '{{ route("fetch.laying-sheet",":id") }}';
    
    $('#laying_planning_detail_id').select2({
        minimumResultsForSearch: Infinity
    });

    $('#laying_planning_detail_id').on('change', async function(e){
        let selected_laying_sheet = $('#laying_planning_detail_id').val();
        url = url_get_laying_sheet.replace(':id',selected_laying_sheet);
        let result = await get_using_fetch(url);

        fill_form_input(result.data);
    });

    $('#submit_form').on('click', function(e){
        e.preventDefault();
        if(!$('#laying_planning_detail_id').val()){
            alert("No Laying Sheet belum dipilih")
            return false;
        }
        $("#form_generate_ticket" ).submit();
    })
</script>

<script type="text/javascript">
    function fill_form_input(data){
        $('#gl_number').val(data.gl_number);
        $('#table_number').val(data.table_number);
        $('#style').val(data.style);
        $('#color').val(data.color);
        $('#buyer').val(data.buyer);
        $('#size_ratio').val(data.size_ratio);
    }
</script>
@endpush