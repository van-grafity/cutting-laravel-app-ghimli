@extends('layouts.master')

@section('title', 'Fabric Issue')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    
                    <div class="detail-section my-5 px-5">
                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <table>
                                    <thead>
                                        <tr style="font-weight:700; font-size:20px;">
                                            <td>NO</td>
                                            <td>:</td>
                                            <td>{{ $fabric_requisition->serial_number }}</td>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="text-left">
                                    <tbody class="align-top">
                                        <tr>
                                            <td>GL</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $fabric_requisition->gl_number }}</td>
                                        </tr>
                                        <tr>
                                            <td>Table No</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $fabric_requisition->table_number }}</td>
                                        </tr>
                                        <tr>
                                            <td>Style</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $fabric_requisition->style }}</td>
                                        </tr>
                                        <tr>
                                            <td>Fabric P/O</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $fabric_requisition->fabric_po }}</td>
                                        </tr>
                                        <tr>
                                            <td>No. Laying Sheet</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $fabric_requisition->no_laying_sheet}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @php
                                $total_received_fabric = 0;
                                foreach($fabric_issues as $fabric_issue){
                                    $total_received_fabric += $fabric_issue->yard;
                                }
                            @endphp
                            <div class="col-md-6">
                                <table>
                                    <tbody class="align-top">
                                        <tr>
                                            <td>Fabric Type</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $fabric_requisition->fabric_type }}</td>
                                        </tr>
                                        <tr>
                                            <td>Color</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $fabric_requisition->color }}</td>
                                        </tr>
                                        <tr>
                                            <td>Quantity Required</td>
                                            <td class="pl-4">:</td>
                                            <td>{{ $fabric_requisition->quantity_required }}</td>
                                        </tr>
                                        <tr>
                                            <td>Quantity Issued</td>
                                            <td class="pl-4">:</td>
                                            <td>
                                                {{ $total_received_fabric }} yards
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Difference</td>
                                            <td class="pl-4">:</td>
                                            <td>
                                                @php
                                                    $resTrim = $fabric_requisition->quantity_required;
                                                    $resTrim = preg_replace("/[^0-9.]/", "", $resTrim);
                                                    $resTrim = intval($resTrim);
                                                    $resTrim = $resTrim - $total_received_fabric;
                                                @endphp
                                                {{ $resTrim }} yards
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <hr style="border-top:2px solid #bbb" class="py-1">

                    <div class="content-title text-center">
                        <h3>Fabric Issue</h3>
                    </div>

                    <div class="row">
                        <div class="text-left mb-3 col-md-6">
                            <div class="dropdown">
                                <button class="btn btn-success dropdown-toggle shadow-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Action
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a hidden href="javascript:void(0);" class="dropdown-item" id="btn_modal_create" onclick="showModalFabricIssue(true)">
                                        <i class="fas fa-plus"></i> Single Issue
                                    </a>
                                    <a HIDD href="javascript:void(0);" class="dropdown-item" id="btn_modal_create" onclick="showModalFabricIssueMultiple(true)">
                                        <i></i> Add Issue
                                    </a>
                                    <a type="button" class="dropdown-item" href="{{ route('fabric-issue.print', $fabric_requisition->id) }}" target="_blank">
                                        <i></i> Print
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table id="fabric_issue_table" class="table table-bordered table-hover">
                        <thead class="">
                            <tr>
                                <th scope="col" class="">No. </th>
                                <th scope="col" class="">Roll No</th>
                                <th scope="col" class="">Batch No</th>
                                <th scope="col" class="">Weight</th>
                                <th scope="col" class="">Yard</th>
                                <th scope="col" class="">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($fabric_issues->count() > 0)
                                @foreach($fabric_issues as $fabric_issue)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $fabric_issue->roll_no }}</td>
                                    <td>{{ $fabric_issue->batch_number }}</td>
                                    <td>{{ $fabric_issue->weight }}</td>
                                    <td>{{ $fabric_issue->yard }}</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-danger" onclick="delete_fabricIssue({{ $fabric_issue->id }})">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="showModalFabricIssue(false, {{ $fabric_issue->id }})">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center">No Data</td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot class="text-center" style="background: #eee;">
                            <tr class="text-center">
                                <th colspan="4" class="text-right">Total Yard</th>
                                <th colspan="2" class="text-left">{{ $fabric_issues->sum('yard') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                    
                </div>
                <div class="card-footer text-right">
                        <a href="{{ route('fabric-issue.index') }}" class="btn btn-secondary">Back</a>
                    </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">
                    Fabric Issue
                </h5>
            </div>
            <form action="{{ route('fabric-issue.store') }}" method="POST" class="custom-validation" enctype="multipart/form-data" id="fabricCons_form">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <input type="hidden" name="fabric_requisition_id" value="{{ $fabric_requisition->id }}">
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <label for="roll_no">Roll No</label>
                            <input type="text" class="form-control" id="roll_no" name="roll_no" placeholder="Roll No">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <label for="batch_number">Batch No</label>
                            <input type="text" class="form-control" id="batch_number" name="batch_number" placeholder="Batch No">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <label for="weight">Weight</label>
                            <input type="text" class="form-control" id="weight" name="weight" placeholder="Weight">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <label for="yard">Yard</label>
                            <input type="text" class="form-control" id="yard" name="yard" placeholder="Yard">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary shadow-sm" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary shadow-sm">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="modal_formLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_formLabel">Fabric Issue Multiple</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('fabric-issue.store') }}" method="POST" class="custom-validation" enctype="multipart/form-data" id="fabric_issue_form">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="text-right" style="margin-bottom: 10px; margin-top: -26px;">
                                    <button type="button" class="btn btn-sm btn-primary" id="btn_add_issue">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    <span hidden id="serial_number" name="serial_number"> {{ $fabric_requisition->serial_number }} </span>
                                </div>
                                <table class="table table-bordered table-hover" id="table_issue">
                                    <thead>
                                        <tr>
                                            <th>Roll No</th>
                                            <th>Bath No</th>
                                            <th>Weight</th>
                                            <th>Yard</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <input type="hidden" name="fabric_requisition_id" value="{{ $fabric_requisition->id }}">
                                            <td>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="roll_no" name="roll_no[]">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="batch_number" name="batch_number[]">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="weight" name="weight[]">
                                                </div>
                                            </td>
                                            <td>
                                            <div class="form-group">
                                                    <input type="text" class="form-control" id="yard" name="yard[]">
                                                </div>
                                            </td>
                                            <td class="text-center" style="vertical-align: middle;">
                                                <button type="button" class="btn btn-sm btn-danger" id="btn_remove_issue">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btn_submit_modal">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
    function showModalFabricIssue(add, id = null) {
        var modal = $('#exampleModalLong'),
            form = $('#fabricCons_form');
        if (add) {
            $('#exampleModalLong').modal('show');
            $('#exampleModalLongTitle').text('Create Fabric Issue');
            $('#roll_no').val('');
            $('#batch_number').val('');
            $('#weight').val('');
            $('#yard').val('');
            $('#fabricCons_form').attr('action', "{{ route('fabric-issue.store') }}");
        } else {
            // form.trigger('reset').parsley().reset();
            form.attr('action', "{{ route('fabric-issue.update', ':id') }}".replace(':id', id));
            form.find('[name="_method"]').val('PUT');
            $('#exampleModalLongTitle').text('Edit Fabric Issue');
            $.ajax({
                url: "{{ url('fabric-issue') }}" + '/' + id + '/edit',
                type: "GET",
                dataType: "JSON",
                success: function (data) {
                    $('#exampleModalLong').modal('show');
                    $('#exampleModalLongTitle').text('Edit Fabric Issue');
                    $('#roll_no').val(data.roll_no);
                    $('#batch_number').val(data.batch_number);
                    $('#weight').val(data.weight);
                    $('#yard').val(data.yard);
                    $('#btn_submit_modal').show();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }
    }

    function showModalFabricIssueMultiple(add, id = null) {
        var modal = $('#modal_form'),
            form = $('#fabric_issue_form');
        if (add) {
            $('#modal_form').modal('show');
            $('#modal_formLabel').text('Create Fabric Issue Multiple');
            $.ajax({
                url: "{{ url('fabric-issue') }}" + '/' + id + '/edit',
                type: "GET",
                dataType: "JSON",
                success: function (data) {
                    $('#modal_form').modal('show');
                    $('#modal_formLabel').text('Create Fabric Issue Multiple');
                    $('#roll_no').val(data.roll_no);
                    $('#batch_number').val(data.batch_number);
                    $('#weight').val(data.weight);
                    $('#yard').val(data.yard);
                    $('#btn_submit_modal').show();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
            $('#fabric_issue_form').attr('action', "{{ route('fabric-issue.store') }}");
        } else {
            // form.trigger('reset').parsley().reset();
            form.attr('action', "{{ route('fabric-issue.update', ':id') }}".replace(':id', id));
            form.find('[name="_method"]').val('PUT');
            $('#modal_formLabel').text('Edit Fabric Issue Multiple');
            
            $.ajax({
                url: "{{ url('fabric-issue') }}" + '/' + id + '/edit',
                type: "GET",
                dataType: "JSON",
                success: function (data) {
                    $('#modal_form').modal('show');
                    $('#modal_formLabel').text('Edit Fabric Issue Multiple');
                    $('#roll_no').val(data.roll_no);
                    $('#batch_number').val(data.batch_number);
                    $('#weight').val(data.weight);
                    $('#yard').val(data.yard);
                    $('#btn_submit_modal').show();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }
    }

    function delete_fabricIssue(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "{{ url('fabric-issue') }}" + '/' + id,
                    type: "POST",
                    data: {
                        '_method': 'DELETE',
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        location.reload();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: errorThrown
                        });
                    }
                });
            }
        });
    }
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#btn_add_issue').click(function() {
            var html = '';
            html += '<tr>';
            html += '<td><input type="text" class="form-control" id="roll_no" name="roll_no[]"></td>';
            html += '<td><input type="text" class="form-control" id="batch_number" name="batch_number[]"></td>';
            html += '<td><input type="text" class="form-control" id="weight" name="weight[]"></td>';
            html += '<td><input type="text" class="form-control" id="yard" name="yard[]"></td>';
            html += '<td class="text-center"><button type="button" class="btn btn-sm btn-danger" id="btn_remove_issue"><i class="fas fa-minus"></i></button></td>';
            html += '</tr>';
            $('#table_issue').append(html);
        });

        $(document).on('click', '#btn_remove_issue', function() {
            $(this).closest('tr').remove();
        });
    });
</script>
@endpush