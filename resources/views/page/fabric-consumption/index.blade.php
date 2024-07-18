@extends('layouts.master')
@section('title', $title)
@section('content')
    <div class="row justify-content-center pt-5">
        <div class="col-lg-6 col-md-8 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title my-auto"> {{ $page_title }} </h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="buyer" class="form-label">Buyer</label>
                                <div class="">
                                    <select class="form-control" id="buyer" name="buyer"
                                        data-placeholder="Choose Buyer">
                                        <option value=""></option>
                                        @foreach ($buyers as $buyer)
                                            <option value="{{ $buyer->id }}">{{ $buyer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="gl_number" class="form-label">GL Number</label>
                                <div class="select2-navy">
                                    <select class="form-control select2" id="gl_number" name="gl_number[]"
                                        multiple="multiple" data-placeholder="Choose GL Number">
                                        @foreach ($gls as $gl)
                                            <option value="{{ $gl->id }}">{{ $gl->gl_number }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <a href="javascript:void(0);" class="btn btn-secondary" id="btn_preview">Preview Report</a>
                            <a href="javascript:void(0);" class="btn btn-primary" id="btn_print">Print Report</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="preview_section" style="display:none">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <table id="fabric_consumption_table" class="table table-bordered table-hover text-center">
                        <thead>
                            <tr class="">
                                <th width="5px">No</th>
                                <th width="100px">GL Number</th>
                                <th width="">Color</th>
                                <th width="">Consumption Plan</th>
                                <th width="">Actual Consumpt</th>
                                <th width="">Balance</th>
                                <th width="">Completion</th>
                                <th width="">Replacement</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const dtable_preview_url = "{{ route('fabric-consumption.print-preview') }}";
        const print_url = "{{ route('fabric-consumption.print') }}"
    </script>
    <script type="text/javascript">
        $('#buyer, #gl_number').select2({});

        let fabric_consumption_table = $('#fabric_consumption_table').DataTable({
            processing: true,
            serverSide: true,
            deferLoading: 0,
            ajax: {
                url: dtable_preview_url,
                data: function(d) {
                    d.buyer = $('#buyer').val();
                    d.gl_number = $('#gl_number').val();
                },
                beforeSend: function() {
                    // ## Tambahkan kelas dimmed-table sebelum proses loading dimulai
                    $('#farbic_request_table').addClass('dimmed-table').append(
                        '<div class="datatable-overlay"></div>');
                },
                complete: function() {
                    // ## Hapus kelas dimmed-table setelah proses loading selesai
                    $('#farbic_request_table').removeClass('dimmed-table').find('.datatable-overlay').remove();
                    $('[data-toggle="tooltip"]').tooltip();
                },
            },
            order: [],
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'gl_number',
                    name: 'gl_number'
                },
                {
                    data: 'color',
                    name: 'color'
                },
                {
                    data: 'planning_consumption',
                    name: 'planning_consumption'
                },
                {
                    data: 'actual_consumption',
                    name: 'actual_consumption'
                },
                {
                    data: 'balance',
                    name: 'balance'
                },
                {
                    data: 'completion',
                    name: 'completion'
                },
                {
                    data: 'replacement',
                    name: 'replacement'
                },
            ],
            columnDefs: [{
                targets: [0, 1, 2, 3, 4, 5, 6, 7],
                orderable: false,
                searchable: false
            }, ],
            orderable: false,
            paging: false,
            responsive: true,
            lengthChange: true,
            searching: false,
            autoWidth: false,
            searchDelay: 500,
        });

        $('#btn_preview').on('click', function(event) {
            $('#preview_section').show();

            $('#btn_preview').addClass('loading').attr('disabled', true);
            $(this).addClass('loading').attr('disabled', true);
            fabric_consumption_table.ajax.reload(function(json) {
                $('#btn_preview').removeClass('loading').attr('disabled', false);
            });
        });

        $('#btn_print').on('click', function(event) {
            buyer = $('#buyer').val();
            let gl_ids = [];
            $('#gl_number option:selected').each(function() {
                gl_ids.push($(this).val());
            });
            if (gl_ids.length > 0 || buyer) {
                let gl_ids_str = gl_ids.join(',');
                window.open(print_url + '?gl_number=' + gl_ids_str + '&buyer=' + buyer, '_blank');
            } else {
                alert('Please select GL Number or Buyer');
            }
        })
    </script>
@stop
