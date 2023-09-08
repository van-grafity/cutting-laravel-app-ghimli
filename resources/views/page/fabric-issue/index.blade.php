@extends('layouts.master')

@section('title', 'Fabric Issue')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="content-title text-center">
                        <h3>Fabric Issue</h3>
                    </div>
                    <table class="table table-bordered table-hover" id="fabric_issue_table">
                        <thead class="">
                        <tr>
                            <th scope="col" class="">No. </th>
                                <th scope="col" class="">Serial Number</th>
                                <th width="18px" scope="col" class="">GL Number</th>
                                <th scope="col" class="">Style No</th>
                                <th scope="col" class="">P/O No</th>
                                <th scope="col" class="">Color</th>
                                <th width="8%" scope="col" class="">Status</th>
                                <th width="8%" scope="col" class="">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('js')
<script type="text/javascript">
    $(function (e) {
        $('#fabric_issue_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('fabric-issue-data') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'serial_number', name: 'serial_number'},
                {data: 'gl_number', name: 'gl_number'},
                {data: 'style_no', name: 'style_no'},
                {data: 'fabric_po', name: 'fabric_po'},
                {data: 'color', name: 'color'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
    });
</script>
@endpush