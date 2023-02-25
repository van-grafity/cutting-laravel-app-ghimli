@extends('layouts.master')

@section('title', 'Cutting')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="search-box me-2 mb-2 d-inline-block">
                            <div class="position-relative">
                                <input type="text" class="form-control searchTable"
                                    placeholder="Search">
                                <i class="bx bx-search-alt search-icon"></i>
                            </div>
                        </div>
                        <a href="javascript:void(0);" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2"
                            onclick="modalCutting(true)"><i class="bx bx-plus font-size-16 align-middle me-2"></i>
                            Create
                        </a>
                    </div>
                    <table class="table align-middle table-nowrap table-hover table-datatable w-100">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" style="width: 70px;">#</th>
                                <th scope="col">Job Number</th>
                                <th scope="col">Style Number</th>
                                <th scope="col">Table Number</th>
                                <th scope="col">Next Bundling</th>
                                <th scope="col">Color</th>
                                <th scope="col">Size</th>
                                <th scope="col" style="width: 100px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($datasCutting as $dataCutting)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $dataCutting->job_number }}</td>
                                    <td>{{ $dataCutting->style_number }}</td>
                                    <td>{{ $dataCutting->table_number }}</td>
                                    <td>{{ $dataCutting->next_bundling }}</td>
                                    <td>{{ $dataCutting->color }}</td>
                                    <td>{{ $dataCutting->size }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="javascript:void(0);" class="btn btn-primary btn-sm"
                                                onclick="modalCutting(false, {{ $dataCutting->id }})">Edit</a>
                                            <form action="{{ url('cutting',$dataCutting->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="modalCutting">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('/cutting') }}" method="POST" class="custom-validation" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col-md-6">
                            <label for="foatgbi">Job Number</label>
                                <input type="text" class="form-control" id="job_number" placeholder="Enter Job Number" required
                                    name="job_number">
                            </div>
                            <div class="col-md-6">
                            <label for="foatgbi">Style Number</label>
                                <input type="text" class="form-control" id="style_number" placeholder="Enter Style Number" required
                                    name="style_number">
                            </div>
                            <div class="col-md-6">
                                <label for="foatgbi">Table Number</label>
                                <input type="text" class="form-control" id="table_number" placeholder="Enter Table Number" required
                                    name="table_number">
                            </div>
                            <div class="col-md-6">
                                <label for="foatgbi">Next Bundling</label>
                                <input type="text" class="form-control" id="next_bundling" placeholder="Enter Next Bundling" required
                                    name="next_bundling">
                            </div>
                            <div class="col-md-6">
                                <label for="foatgbi">Color</label>
                                <input type="text" class="form-control" id="color" placeholder="Enter Color" required name="color">
                                
                            </div>
                            <div class="col-md-6">
                                <label for="foatgbi">Size</label>
                                <input type="text" class="form-control" id="size" placeholder="Enter Size" required name="size">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light mt-2">Submit</button>
                                    <button type="button" class="btn btn-secondary waves-effect waves-light mt-2" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script type="text/javascript">

    const app_url = {!! json_encode(url('/')) !!}
    
    function modalCutting(add, id = null, url) {
        var modal = $('#modalCutting'),
            form = modal.find('form');
        if (add) {
            modal.find('.modal-title').text('Add Cutting');
            // form.attr('action', '/cutting');
            form.attr('method', 'POST');
            form.find('input[name="_method"]').remove();
            form.find('input[name="job_number"]').val('');
            form.find('input[name="style_number"]').val('');
            form.find('input[name="table_number"]').val('');
            form.find('input[name="next_bundling"]').val('');
            form.find('input[name="color"]').val('');
            form.find('input[name="size"]').val('');
            modal.modal('show');
        } else {
            modal.find('.modal-title').text('Edit Cutting');
            form.attr('action', app_url+'/cutting/' + id);
            form.attr('method', 'POST');
            form.append('<input type="hidden" name="_method" value="PUT">');
            $.ajax({
                url: app_url+'/cutting/' + id,
                method: 'GET',
                success: function (res) {
                    form.find('input[name="job_number"]').val(res.job_number);
                    form.find('input[name="style_number"]').val(res.style_number);
                    form.find('input[name="table_number"]').val(res.table_number);
                    form.find('input[name="next_bundling"]').val(res.next_bundling);
                    form.find('input[name="color"]').val(res.color);
                    form.find('input[name="size"]').val(res.size);
                }
            }).then(function () {
                modal.modal('show');
            });
        }
    }

</script>
@endpush