<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cut Piece Stock Report</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style type="text/css">
        @page {
            margin-top: 1cm;
            margin-left: 1.5cm;
            margin-right: 2cm;
            margin-bottom: 1cm;
        }

		  table > thead > tr > th{
        }

        .table thead th {
            border: 1px black solid;
            text-align: center;
            vertical-align: middle;
            font-size: 10px;
            padding-top: 2 !important;
            padding-bottom: 2 !important;
        }
        
        .table tbody td {
            border: 1px black solid;
            vertical-align: middle;
            font-weight: bold;
            font-size: 10px;
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
    $size_all = [];
    foreach ($data as $key => $item)
    {
        foreach ($item->layingPlanningDetail as $key => $detail)
        {
            foreach ($detail->layingPlanningDetailSize as $size)
            {
                if (!in_array($size->size->size, $size_all))
                {
                    array_push($size_all, $size->size->size);
                }
            }
        }
    }
    $layer = 0;
    foreach ($data as $key => $item)
    {
        foreach ($item->layingPlanningDetail as $key => $detail)
        {
            foreach ($detail->cuttingOrderRecord->cuttingTicket as $key => $ticket)
            {
                $layer += $ticket->layer;
            }
        }
    }
@endphp
<body>
    <div>
        <div class="row">
            <div class="col-12">
                <div class="content-title text-center">
                    <h3>Cut Piece Stock Report</h3>
                </div>

                </br>

                <table class="table" id="cut_piece_stock_table">
                    <thead class="">
                        <tr>
                            <th scope="col" rowspan="2">No.</th>
                            <th scope="col" rowspan="2">GL No.</th>
                            <th scope="col" rowspan="2">Color</th>
                            <th scope="col" colspan="{{ count($size_all) }}">Size</th>
                            <th scope="col" rowspan="2">Total</th>
                        </tr>
                        <tr>
                            @foreach ($size_all as $key => $size)
                                <th scope="col">{{ $size }}</th>
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
                                <td width="1%" style="text-align: center;">{{ $loop->iteration }}</td>
                                <td width="12%" style="text-align: center;">{{ $item->gl->gl_number }}</td>
                                <td>{{ $item->color->color }}</td>
                                @foreach ($size_all as $key => $size)
                                    <td width="4%" style="text-align: center;">
                                        @php
                                            $total = 0;
                                        @endphp
                                        @foreach ($item->layingPlanningDetail as $key => $detail)
                                            @foreach ($detail->cuttingOrderRecord->cuttingTicket as $key => $ticket)
                                                @foreach ($bundle_cuts as $key => $bundle)
                                                    @if ($ticket->id == $bundle->ticket_id)
                                                        @if ($size == $ticket->size->size)
                                                            @foreach ($detail->layingPlanningDetailSize as $key => $layingSize)
                                                                @if ($layingSize->size_id == $ticket->size_id)
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
                                <td width="4%" style="text-align: center;">
                                    @php
                                        $total = 0;
                                    @endphp
                                    @foreach ($item->layingPlanningDetail as $key => $detail)
                                        @foreach ($detail->cuttingOrderRecord->cuttingTicket as $key => $ticket)
                                            @foreach ($bundle_cuts as $key => $bundle)
                                                @if ($ticket->id == $bundle->ticket_id)
                                                    @foreach ($detail->layingPlanningDetailSize as $key => $layingSize)
                                                        @if ($layingSize->size_id == $ticket->size_id)
                                                            @php
                                                                $total += $ticket->layer * $layingSize->ratio_per_size;
                                                            @endphp
                                                        @endif
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