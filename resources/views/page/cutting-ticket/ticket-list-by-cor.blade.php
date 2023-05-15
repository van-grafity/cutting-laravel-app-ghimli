@extends('layouts.master')

@section('title', 'Cutting Ticket')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="content-title text-center">
                        <h3>Cutting Ticket List</h3>
                    </div>
                    <!-- <div class="d-flex justify-content-end mb-1">
                        <a href="{{ route('cutting-ticket.create') }}" class="btn btn-success mb-2" id="btn_modal_create">Create</a>
                        <a href="{{ route('cutting-ticket.print-multiple', 1) }}" class="btn btn-primary mb-2" id="btn_modal_create">Print</a>
                    </div> -->

                    <table class="table table-bordered table-hover" id="cutting_ticket_table">
                        <thead class="">
                            <tr>
                                <th scope="col">No. </th>
                                <th scope="col" width="130">Ticket Number</th>
                                <th scope="col">No. Laying Sheet</th>
                                <th scope="col">Table No.</th>
                                <th scope="col">Color</th>
                                <th scope="col">Size</th>
                                <th scope="col">Layer</th>
                                <th scope="col" width="120">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
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
        $('#cutting_ticket_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('cutting-ticket-detail-data', $serial_number) }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'ticket_number', name: 'ticket_number'},
                {data: 'no_laying_sheet', name: 'no_laying_sheet'},
                {data: 'table_number', name: 'table_number'},
                {data: 'color', name: 'color'},
                {data: 'size', name: 'size'},
                {data: 'layer', name: 'layer'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            llengthChange: true,
            searching: true,
            autoWidth: false,
            responsive: true,
        });
    });
</script>
@endpush