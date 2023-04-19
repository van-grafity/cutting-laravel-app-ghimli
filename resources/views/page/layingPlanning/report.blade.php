<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print PDF</title>

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
            height:25px;
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
                    <td>:</td>
                    <td><span>{{ $data->buyer->name }}</span></td>
                </tr>
                <tr style="padding-top: 2px; padding-bottom: 2px;">
                    <td width="14%">Style</td>
                    <td>:</td>
                </tr>
                    <tr style="padding-top: 2px; padding-bottom: 2px;">
                    <td width="14%">GL</td>
                    <td>:</td>
                </tr>
                <tr style="padding-top: 2px; padding-bottom: 2px;">
                    <td width="14%">Color</td>
                    <td>:</td>
                </tr>
            </table>
            <br/>
            @php
                $length = count($data->layingPlanningSize);
            @endphp
            <table class="table table-bordered" style="font-size: 14px;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Batch #</th>
                        <th colspan="{{ $length }}">Size/Order</th>
                        <th>Total</th>
                        <th>Yds</th>
                        <th>Marker</th>
                        <th colspan="1"></th>
                        <th colspan="3">Marker</th>
                        <th colspan="{{ $length }}">Ratio</th>
                        <th>Lay</th>
                        <th>Cut</th>
                        <th colspan="1"></th>
                        <th>Layer</th>
                        <th>Cutter</th>
                        <th>Emb</th>
                        <th>Sew</th>
                    </tr>
                    <tr>
                        <th>Laying</th>
                        <th>No</th>
                        @foreach ($data->layingPlanningSize as $item)
                        <th>{{ $item->size->size }}</th>
                        @endforeach
                        <th colspan="1"></th>
                        <th>Qty</th>
                        <th>Code</th>
                        <th>LOT</th>
                        <th>Length</th>
                        <th>Yds</th>
                        <th>Inch</th>
                        @foreach ($data->layingPlanningSize as $item)
                        <th>{{ $item->size->size }}</th>
                        @endforeach
                        <th>Qty</th>
                        <th>Qty</th>
                        <th>Date</th>
                        <th colspan="1"></th>
                        <th colspan="1"></th>
                        <th>Print</th>
                        <th>Line</th>
                    </tr>
                    <tr>
                        <th>Sheet</th>
                        <th colspan="1"></th>
                        <th colspan="{{ $length }}"></th>
                        <th colspan="1"></th>
                        <th colspan="1"></th>
                        <th colspan="1"></th>
                        <th colspan="1"></th>
                        <th colspan="1"></th>
                        <th colspan="1"></th>
                        <th colspan="1"></th>
                        <th colspan="{{ $length }}"></th>
                        <th colspan="1"></th>
                        <th colspan="1"></th>
                        <th colspan="1"></th>
                        <th colspan="1"></th>
                        <th colspan="1"></th>
                        <th colspan="1"></th>
                        <th colspan="1"></th>
                </thead>

                <tbody>
                    
                </tbody>
            </table>
        </table>
    </div>
</body>


</html>
