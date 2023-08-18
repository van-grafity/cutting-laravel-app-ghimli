@extends('layouts.master')

@section('title', 'Create Cutting Ticket')

@section('content')

<div class="container-fluid">
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
                                    <label for="cutting_order_id" class="form-label">No. Cutting Order</label>
                                        <select class="form-control" id="cutting_order_id" name="cutting_order_id" style="width: 100%;" data-placeholder="Choose Cutting Order">
                                        <option value="">Choose Cutting Order</option>
                                        @foreach ( $cutting_order_records as $cutting_order )
                                            <option value="{{ $cutting_order->id }}">{{ $cutting_order->serial_number }}</option>
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
    
    const url_get_cutting_order = '{{ route("fetch.cutting-order",":id") }}';
    
    $('#cutting_order_id').select2({});

    $('#cutting_order_id').on('change', async function(e){
        let selected_cutting_order = $('#cutting_order_id').val();
        url = url_get_cutting_order.replace(':id',selected_cutting_order);
        let result = await get_using_fetch(url);

        fill_form_input(result.data);
    });
    
    async function loading(){
        Swal.fire({
            title: 'Loading',
            html: 'Please wait..',
            allowOutsideClick: false,
            showConfirmButton: false,
            onBeforeOpen: () => {
                Swal.showLoading()
            },
        });
    }

    $('#submit_form').on('click', async function(e){
        e.preventDefault();
        if(!$('#cutting_order_id').val()){
            alert("No Cutting Order belum dipilih")
            return false;
        }
        loading();
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