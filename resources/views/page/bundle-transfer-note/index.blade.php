@extends('layouts.master')

@section('title', 'Cut Piece Transfer Note')

@section('content')
<style>
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="content-title text-center">
                        <h3>Cut Piece Transfer Note List</h3>
                    </div>
                    <table class="table table-bordered table-hover text-center" id="transfer_note_table">
                        <thead class="">
                            <tr>
                                <th wihth="5%;">No.</th>
                                <th wihth="20%;">Serial Number</th>
                                <th wihth="10%;">GL Number</th>
                                <th wihth="10%;">Color</th>
                                <th wihth="10%;">Location</th>
                                <th wihth="10%;">Date</th>
                                <th wihth="5%;">Total Pcs</th>
                                <th wihth="10%;">Action</th>
                            </tr>
                        </thead>
                    </table>    
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Section -->
<div class="modal fade" id="modal_detail_stock" tabindex="-1" role="dialog" aria-labelledby="modal_detail_stockLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_detail_stockLabel">Detail Stock</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <table class="table table-bordered table-hover text-center" id="detail_transfer_note_table">
                        <thead class="bg-dark">
                            <tr>
                                <th wihth="10px;">No.</th>
                                <th wihth="10px;">Serial Number</th>
                                <th wihth="50px;">GL Number</th>
                                <th wihth="50px;">Location</th>
                                <th wihth="50px;">Hate</th>
                                <th wihth="50px;">Total Bunhle</th>
                                <th wihth="10px;">Total Pcs</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot class="bg-dark">
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Oke</button>
            </div>
        </div>
    </div>
</div>

@endsection
@push('js')
<script type="text/javascript">

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const detail_url ='{{ route("bundle-stock.detail") }}';
    
    async function detail_stock(laying_planning_id) {

        let data_params = { token, laying_planning_id };
        result = await using_fetch(detail_url, data_params);

        if(result.status == "error"){
            swal_failed({ title: result.message });
            return false;   
        }

        insert_detail_stock_to_table(result.data.detail_stock)
        $('#modal_detail_stock').modal('show');

    };

    const insert_detail_stock_to_table = (detail_stock) => {
        $('#detail_transfer_note_table tbody').html('');
        $('#detail_transfer_note_table tfoot').html('');
        
        let total = 0;

        detail_stock.forEach((data, key) => {
            let row = `
                <tr>
                    <td>${key+1}</td>
                    <td>${data.gl_number}</td>
                    <td class="text-left">${data.color}</td>
                    <td>${data.size}</td>
                    <td>${data.current_qty}</td>
                </tr>
            `;
            $('#detail_transfer_note_table tbody').append(row);
            
            total += parseInt(data.current_qty);
        });
        let row_footer = `
            <tr>
                <td colspan="4" class="text-right">Total PCS :</td>
                <td colspan="1">${total}</td>
            </tr>
        `;
        $('#detail_transfer_note_table tfoot').html(row_footer);
    }

</script>

<script type="text/javascript">
    $(function (e) {
        $('#transfer_note_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('bundle-transfer-note/dtable') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'serial_number', name: 'serial_number'},
                {data: 'gl_number', name: 'gl_number'},
                {data: 'color', name: 'color'},
                {data: 'location', name: 'location'},
                {data: 'date', name: 'date'},
                {data: 'total_pcs', name: 'total_pcs'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
        });
    });
</script>

<script type="text/javascript">
</script>
@endpush