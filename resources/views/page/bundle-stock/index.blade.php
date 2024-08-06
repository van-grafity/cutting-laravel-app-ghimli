@extends('layouts.master')

@section('title', 'Cut Piece Stock')

@section('content')
<style>
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="content-title text-center">
                        <h3>Cut Piece Stock List</h3>
                    </div>
                    <table class="table table-bordered table-hover text-center" id="bundle_stock_table">
                        <thead class="">
                            <tr>
                                <th width="10px;">No. </th>
                                <th width="100px">GL Number</th>
                                <th width="">Color</th>
                                <th width="">Total</th>
                                <th width="">Action</th>
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
                    <table class="table table-bordered table-hover text-center" id="detail_bundle_stock_table">
                        <thead class="bg-dark">
                            <tr>
                                <th wihth="10px;">No.</th>
                                <th wihth="10px;">GL Number</th>
                                <th wihth="50px;">Color</th>
                                <th wihth="10px;">Size</th>
                                <th wihth="10px;">Qty</th>
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

    // ## Show Flash Message
    let session = {!! json_encode(session()->all()) !!};
    show_flash_message(session);
    
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
        $('#detail_bundle_stock_table tbody').html('');
        $('#detail_bundle_stock_table tfoot').html('');
        
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
            $('#detail_bundle_stock_table tbody').append(row);
            
            total += parseInt(data.current_qty);
        });
        let row_footer = `
            <tr>
                <td colspan="4" class="text-right">Total PCS :</td>
                <td colspan="1">${total}</td>
            </tr>
        `;
        $('#detail_bundle_stock_table tfoot').html(row_footer);
    }

</script>

<script type="text/javascript">
    $(function (e) {
        $('#bundle_stock_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('bundle-stock/dtable') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'gl_number', name: 'gl_number'},
                {data: 'color', name: 'color'},
                {data: 'total', name: 'total'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
        });
    });
</script>

<script type="text/javascript">
</script>
@endpush