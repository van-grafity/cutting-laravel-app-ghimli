<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cut Piece Stock Report</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <style type="text/css">
        @page {
            margin-top: 1cm;
            margin-left: 1.5cm;
            margin-right: 1.5cm;
            margin-bottom: 1cm;
        }

		  table > thead > tr > th{
            border : 1px dashed black;
        }

        .table thead th {
            border: 1px dashed black;
            text-align: center;
            vertical-align: middle;
            font-size: 8px;
            padding-top: 2 !important;
            padding-bottom: 2 !important;
        }
        
        .table tbody td {
            border: 1px dashed black;
            vertical-align: middle;
            font-weight: bold;
            font-size: 8px;
            padding-top: 2 !important;
            padding-bottom: 2 !important;
            padding-left: 5 !important;
            padding-right: 5 !important;
        }
        
	</style>
</head>
@php
    foreach ($data as $key => $item) {
        $sizeCount = $item->layingPlanningSize->count();
    }
@endphp
<body>
    <div>
        <div class="row">
            <div class="col-12">
                <div class="content-title text-center">
                    <h3>Cut Piece Stock Report</h3>
                </div>

                <table class="table table-bordered table-hover" id="cut_piece_stock_table">
                    <thead class="">
                        <tr>
                            <th scope="col" rowspan="2" width="4%">No.</th>
                            <th scope="col" rowspan="2" width="6%">GL No.</th>
                            <th scope="col" rowspan="2">Color</th>
                            <th scope="col" colspan="{{ $sizeCount }}">Size</th>
                            <th scope="col" rowspan="2" width="4%">Total</th>
                        </tr>
                        <tr>
                            @foreach ($data as $key => $item)
                                @foreach ($item->layingPlanningSize as $key => $size)
                                    <th scope="col" width="4%">{{ $size->size->size }}</th>
                                @endforeach
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $item)
                            <!-- reference -->
                            <!-- @foreach ($item->layingPlanningDetail as $key => $detail)
                                @foreach ($detail->cuttingOrderRecord->cuttingTicket as $key => $ticket)
                                    @foreach ($bundle_cuts as $key => $bundle)
                                        @if ($ticket->id == $bundle->ticket_id)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $ticket->serial_number }}</td>
                                                <td>
                                                    {{ $item->color->color }}
                                                </td>
                                                <td></td>
                                                <td>
                                                    {{ $bundle->bundleStatus->status }}
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endforeach
                            @endforeach -->
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->gl->gl_number }}</td>
                                <td>{{ $item->color->color }}</td>
                                @foreach ($item->layingPlanningSize as $key => $size)
                                    <td>
                                        @php
                                            $total = 0;
                                        @endphp
                                        @foreach ($item->layingPlanningDetail as $key => $detail)
                                            @foreach ($detail->cuttingOrderRecord->cuttingTicket as $key => $ticket)
                                                @foreach ($bundle_cuts as $key => $bundle)
                                                    @if ($ticket->id == $bundle->ticket_id)
                                                        @if ($size->size_id == $ticket->size_id)
                                                            @foreach ($detail->layingPlanningDetailSize as $key => $layingSize)
                                                                @if ($layingSize->size_id == $size->size_id)
                                                                    @php
                                                                        $total += $ticket->layer * $layingSize->ratio_per_size;
                                                                    @endphp
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                        {{ $total }}
                                    </td>
                                @endforeach
                                <td>
                                    @php
                                        $total = 0;
                                    @endphp
                                    @foreach ($item->layingPlanningDetail as $key => $detail)
                                        @foreach ($detail->cuttingOrderRecord->cuttingTicket as $key => $ticket)
                                            @foreach ($bundle_cuts as $key => $bundle)
                                                @if ($ticket->id == $bundle->ticket_id)
                                                    @foreach ($detail->layingPlanningDetailSize as $key => $layingSize)
                                                        @php
                                                            $total += $ticket->layer * $layingSize->ratio_per_size;
                                                        @endphp
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                    {{ $total }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>