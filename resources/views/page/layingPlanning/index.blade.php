@extends('layouts.master')

@section('title', 'Laying Planning')
<!-- $table->foreignId('style_id')->constrained('styles')->onDelete('cascade');
$table->foreignId('buyer_id')->constrained('buyers')->onDelete('cascade');
$table->integer('buyer_id');
$table->integer('color_id');
$table->integer('quantity');
$table->date('delivery_date');
$table->date('plan_date');
$table->string('fabric_po');
$table->string('fabric_cons_id');
$table->string('fabric_type_id');
$table->string('fabric_cons_qty');
$table->timestamps(); -->
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
                            <th scope="col" class="text-left">No. </th>
                                <th scope="col" class="text-left">Gl No.</th>
                                <th scope="col" class="text-left">Style</th>
                                <th scope="col" class="text-left">Buyer</th>
                                <th scope="col" class="text-left">Color</th>
                                <th scope="col" class="text-left">Fabric Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $layingPlanning)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-left">{{ $layingPlanning->gl->gl_number }}</td>
                                <td class="text-left">{{ $layingPlanning->style->style }}</td>
                                <td class="text-left">{{ $layingPlanning->buyer->name }}</td>
                                <td class="text-left">{{ $layingPlanning->color->color }}</td>
                                <td class="text-left">{{ $layingPlanning->fabricType->description }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Section -->
<div class="modal fade" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="modal_create_form" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_create_form">Add Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('buyer.store') }}" method="POST" class="custom-validation" enctype="multipart/form-data" id="create_form">
                @csrf
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
                    <button type="submit" class="btn btn-primary" id="btn_submit">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')

<script type="text/javascript">
$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#btn_modal_create').click((e) => {
        reset_form({title: "Add Planning", btn_textx: "Add Plan"});
    })


})
</script>

<script type="text/javascript">
    function reset_form(data = {}) {
        $('#modal_create_form').text(data.title);
        $('#btn_submit').text(data.btn_text);
        $('#create_form').find("input[type=text], textarea").val("");
        $('#create_form').find('input[name="_method"]').remove();
        $('#modal_form').modal('show')
    }
</script>
@endpush('js')