@extends('layouts.master')

@section('title', 'Buyer')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="search-box me-2 mb-2 d-inline-block">
                            <div class="position-relative">
                                <input type="text" class="form-control searchTable" placeholder="Search">
                                <i class="bx bx-search-alt search-icon"></i>
                            </div>
                        </div>
                        <a href="javascript:void(0);" class="btn btn-success mb-2" id="btn_modal_create" data-id='2'>Create</a>
                    </div>
                    <table class="table align-middle table-nowrap table-hover">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">No. </th>
                                <th scope="col">Buyer's Name</th>
                                <th scope="col">Address</th>
                                <th scope="col">Code</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Aeropostale</td>
                                <td>Cempaka Putih no. 25</td>
                                <td>AERO-02</td>
                                <td>EDIT - DELETE</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Modal Section -->
<div class="modal fade" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="modal_formLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_formLabel">Add Buyer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- <form action="{{ url('/buyer') }}" method="POST" class="custom-validation" enctype="multipart/form-data"> -->
                <div class="modal-body">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="buyer_name">Name</label>
                            <input type="text" class="form-control" id="buyer_name" name="name" placeholder="Enter name">
                        </div>
                        <div class="form-group">
                            <label for="buyer_address">Address</label>
                            <input type="text" class="form-control" id="buyer_address" name="address" placeholder="Enter address">
                        </div>
                        <div class="form-group">
                            <label for="buyer_code">Code</label>
                            <input type="text" class="form-control" id="buyer_code" name="code" placeholder="Enter code">
                        </div>
                    </div>
                    <!-- END .card-body -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btn_submit">Add Buyer</button>
                </div>
            <!-- </form> -->
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
    $('#btn_modal_create').click((e) => {
        $('#modal_form').modal('show')
    })
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#btn_submit").click(function(e){
    
        e.preventDefault();
        let url ="{{ route('buyer.store') }}";
        let form_data = {
            'name': $('#buyer_name').val(),
            'address': $('#buyer_address').val(),
            'code': $('#buyer_code').val()
        }
        console.log(url);
        console.log(form_data);
        
        // $.ajax({
        //    type:'POST',
        //    url:url,
        //    data:form_data,
        //    success:function(data){
        //         if($.isEmptyObject(data.error)){
        //             alert(data.success);
        //             location.reload();
        //         } else {
        //             console.log("lah error");
        //             printErrorMsg(data.error);
        //         }
        //    }
        // }).catch((err)=>{
        //     console.log(err);
        // });
    });

    function printErrorMsg (msg) {
        $(".print-error-msg").find("ul").html('');
        $(".print-error-msg").css('display','block');
        $.each( msg, function( key, value ) {
            $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
        });
    }

</script>
@endpush