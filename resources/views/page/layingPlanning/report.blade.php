<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAYING PLANNING & CUTTING REPORT</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <style type="text/css">
        @page {
            margin-top: 1cm;
            margin-left: 1cm;
            margin-bottom: 0cm;
        }
        table tr td,
		table tr th {
			font-size: 8pt;
		}
        .table {
            border: 2px solid;
        }

        .table thead th {
            border: 1px solid;
            vertical-align: middle;
        }
        .table tbody td {
            border: 1px solid;
            font-weight: bold;
            font-size:6pt;
        }
    </style>
</head>
<body>
    <div>
        <div class="header-main">
            <div style="float: left; text-align: left; font-size: 12px;">
                PT. GHIM LI INDONESIA
            </div>
            <div style="clear:left; text-align: center; font-weight: bold; font-size: 14px;">
            LAYING PLANNING & CUTTING REPORT
                <br>
                <div class="subtitle-nota">{{ $data->serial_number }}</div>
            </div>

        </div>
        <br/>

        <table>
            <table width="100%" style="font-size: 14px;">

                <tr style="padding-top: 2px; padding-bottom: 2px;">
                    <td width="14%">Buyer</td>
                    <td>{{ $data->buyer->name }}</td>
                    <td></td>
                    <td></td>
                    <td width="14%">Fabric P/O</td>
                    <td>{{ $data->fabric_po }}</td>
                    <td width="14%">Delivery Date:</td>
                    <td>{{ $data->delivery_date }}</td>
                </tr>

                <tr style="padding-top: 2px; padding-bottom: 2px;">
                    <td width="14%">Style</td>
                    <td>{{ $data->style->style }}</td>
                    <td width="14%">Order Qty</td>
                    <td>{{ $data->order_qty }}</td>
                    <td width="14%">Fabric Type</td>
                    <td>{{ $data->fabricType->description }}</td>
                    <td width="14%">Plan Date:</td>
                    <td>{{ $data->plan_date }}</td>

                </tr style="padding-top: 2px; padding-bottom: 2px;">
                    <td width="14%">GL</td>
                    <td>{{ $data->gl->gl_number }}</td>
                    <td width="14%">Total Qty</td>
                    <td>{{ $data->order_qty }}</td>
                    <td width="14%">Fabric Cons</td>
                    <td>{{ $data->fabricCons->description }}</td>
                </tr>

                <tr style="padding-top: 2px; padding-bottom: 2px;">
                    <td width="14%">Color</td>
                    <td>XXXXX</td>
                    <td width="14%"></td>
                    <td></td>
                    <td width="14%">Description</td>
                    <td>xx</td>
                </tr>
            </table>
            <br/>
            @php
                $length = count($data->layingPlanningSize);
                $total = 0;
                foreach ($data->layingPlanningSize as $item)
                {
                    $total += $item->quantity;
                }
                $total_detail = 0;
                foreach ($data->layingPlanningSize as $size)
                {
                    $total_detail += $size->quantity;
                }
            @endphp
            <table class="table table-bordered" style="font-size: 14px;">
                <thead>
                    <tr>
                        <th style="text-align: center; vertical-align: middle;">No</th>
                        <th style="text-align: center; vertical-align: middle;">Batch #</th>
                        <th colspan="{{ $length }}" style="text-align: center; vertical-align: middle;">Size/Order</th>
                        <th style="text-align: center; vertical-align: middle;">Total</th>
                        <th style="text-align: center; vertical-align: middle;">Yds</th>
                        <th style="text-align: center; vertical-align: middle;">Marker</th>
                        <th colspan="1"></th>
                        <th colspan="3" style="text-align: center; vertical-align: middle;">Marker</th>
                        <th colspan="{{ $length }}" style="text-align: center; vertical-align: middle;">Ratio</th>
                        <th style="text-align: center; vertical-align: middle;">Lay</th>
                        <th style="text-align: center; vertical-align: middle;">Cut</th>
                        <th colspan="1"></th>
                        <th style="text-align: center; vertical-align: middle;">Layer</th>
                        <th style="text-align: center; vertical-align: middle;">Cutter</th>
                        <th style="text-align: center; vertical-align: middle;">Emb</th>
                        <th style="text-align: center; vertical-align: middle;">Sew</th>
                    </tr>
                    <tr>
                        <th style="text-align: center; vertical-align: middle;">Laying</th>
                        <th style="text-align: center; vertical-align: middle;">No</th>
                        @foreach ($data->layingPlanningSize as $item)
                        <th style="text-align: center; vertical-align: middle;">{{ $item->size->size }}</th>
                        @endforeach
                        <th colspan="1"></th>
                        <th style="text-align: center; vertical-align: middle;">Qty</th>
                        <th style="text-align: center; vertical-align: middle;">Code</th>
                        <th style="text-align: center; vertical-align: middle;">LOT</th>
                        <th style="text-align: center; vertical-align: middle;">Length</th>
                        <th style="text-align: center; vertical-align: middle;">Yds</th>
                        <th style="text-align: center; vertical-align: middle;">Inch</th>
                        @foreach ($data->layingPlanningSize as $item)
                        <th style="text-align: center; vertical-align: middle;">{{ $item->size->size }}</th>
                        @endforeach
                        <th style="text-align: center; vertical-align: middle;">Qty</th>
                        <th style="text-align: center; vertical-align: middle;">Qty</th>
                        <th style="text-align: center; vertical-align: middle;">Date</th>
                        <th colspan="1"></th>
                        <th colspan="1"></th>
                        <th style="text-align: center; vertical-align: middle;">Print</th>
                        <th style="text-align: center; vertical-align: middle;">Line</th>
                    </tr>
                    <tr>
                        <th style="text-align: center; vertical-align: middle;">Sheet</th>
                        <th colspan="1"></th>
                        @foreach ($data->layingPlanningSize as $item)
                        <th style="text-align: center; vertical-align: middle;">{{ $item->quantity }}</th>
                        @endforeach
                        <th colspan="1" style="text-align: center; vertical-align: middle;">{{ $total }}</th>
                        <th colspan="1"></th>
                        <th colspan="1"></th>
                        <th colspan="1"></th>
                        <th colspan="1"></th>
                        <th colspan="1"></th>
                        <th colspan="1"></th>
                        @foreach ($data->layingPlanningSize as $item)
                        <th style="text-align: center; vertical-align: middle;">{{ $item->quantity }}</th>
                        @endforeach
                        <th colspan="1"></th>
                        <th colspan="1"></th>
                        <th colspan="1"></th>
                        <th colspan="1"></th>
                        <th colspan="1"></th>
                        <th colspan="1"></th>
                        <th colspan="1"></th>
                </thead>

                <tbody>
                    @foreach ($details as $detail)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td></td>
                        @foreach ($detail->layingPlanning->layingPlanningSize as $item)
                        <td>{{ $item->quantity }}</td>
                        @endforeach
                        <td>{{ $total_detail }}</td>
                        <td>{{ $detail->marker_yard }}</td>
                        <td>{{ $detail->marker_code }}</td>
                        <td>{{ $detail->table_number }}</td>
                        <td>{{ $detail->marker_length }}</td>
                        <td>{{ $detail->marker_yard }}</td>
                        <td>{{ $detail->marker_inch }}</td>
                        @foreach ($detail->layingPlanning->layingPlanningSize as $item)
                        <td>{{ $item->quantity }}</td>
                        @endforeach
                        <td>{{ $detail->layer_qty }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="2" style="text-align: center; vertical-align: middle;">?? PCS FOR SAMPLE</td>
                        @foreach ($data->layingPlanningSize as $item)
                        <td></td>
                        @endforeach
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        @foreach ($data->layingPlanningSize as $item)
                        <td></td>
                        @endforeach
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        {{-- @foreach ($details->layingPlanning->layingPlanningSize as $item) --}}
                        @foreach ($data->layingPlanningSize as $item)
                        <td></td>
                        @endforeach
                        {{-- @endforeach --}}
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        {{-- @foreach ($details->layingPlanning->layingPlanningSize as $item) --}}
                        @foreach ($data->layingPlanningSize as $item)
                        <td></td>
                        @endforeach
                        {{-- @endforeach --}} 
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </table>
    </div>
</body>
</html>
