@extends('layouts.master')

@section('title', 'Cutting Ticket')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="content-title text-center">
                        <h3>Cutting Order Ticket</h3>
                    </div>
                    <div class="d-flex justify-content-end mb-1">
                        <a href="{{ route('cutting-ticket.create') }}" class="btn btn-success mb-2" id="btn_modal_create">Create</a>
                    </div>

                    <table class="table table-bordered table-hover" id="cutting_ticket_table">
                        <thead class="">
                            <tr>
                                <th width="20%" scope="col">Serial Number</th>
                                <th scope="col">Color</th>
                                <th scope="col">Style</th>
                                <th scope="col">Fabric Type</th>
                                <th scope="col">Fabric Cons</th>
                                <th width="10%" scope="col">Action</th>
                            </tr>
                        </thead>
                        <!-- <tbody>
                        </tbody> -->
                    </table>    
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
@push('js')
<script type="text/javascript">
</script>

<script type="text/javascript">
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
    
    $(function (e) {
        $('#cutting_ticket_table').DataTable({
            processing: loading(),
            serverSide: true,
            ajax: "{{ url('/cutting-ticket-data') }}",
            columns: [
                {data: 'ticket_number', name: 'cutting_order_records.serial_number', render: function(data, type, row) {
                    return `<a href="{{ url('/cutting-ticket/detail') }}/${row.id}">${data}</a>`
                }},
                {data: 'color', name: 'color'},
                {data: 'style', name: 'styles.style'},
                {data: 'fabric_type', name: 'fabric_types.name'},
                {data: 'fabric_cons', name: 'fabric_cons.name'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            lengthChange: true,
            searching: true,
            autoWidth: false,
            responsive: true,
            drawCallback: function( settings ) {
                Swal.close();
            }
        });
    });
    function delete_ticket(id){
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this ticket!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('/cutting-ticket/delete') }}"+'/'+id,
                    type: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id
                    },
                    success: function(data) {
                        if (data.status == 'success') {
                            Swal.fire(
                                'Deleted!',
                                data.message,
                                'success'
                            )
                            $('#cutting_ticket_table').DataTable().ajax.reload();
                        } else {
                            Swal.fire(
                                'Failed!',
                                data.message,
                                'error'
                            )
                        }
                    },
                    error: function(data) {
                        Swal.fire(
                            'Failed!',
                            'Something wrong',
                            'error'
                        )
                    }
                });
            }
        })
    }
    
    

    function refresh_ticket(id){
        Swal.fire({
            title: 'Are you sure?',
            text: "Refresh Ticket will delete all existing ticket and recreate it",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, refresh it!',
            allowOutsideClick: false,
        }).then((result) => {
            if (result.isConfirmed) {
                loading();
                $.ajax({
                    url: "{{ url('/cutting-ticket/refresh-ticket') }}"+'/'+id,
                    type: 'GET',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id
                    },
                    success: function(data) {
                        if (data.status == 'success') {
                            Swal.fire(
                                'Refreshed!',
                                data.message,
                                'success'
                            )
                            $('#cutting_ticket_table').DataTable().ajax.reload();
                        } else {
                            Swal.fire(
                                'Failed!',
                                data.message,
                                'error'
                            )
                        }
                    },
                    error: function(data) {
                        Swal.fire(
                            'Failed!',
                            'Something wrong',
                            'error'
                        )
                    }
                });
            }
        })
    }
</script>
@endpush