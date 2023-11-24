<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracking Fabric Usage Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <style type="text/css">
        .table-tracking thead th {
            border: 1px black solid;
            text-align: center;
            vertical-align: middle;
            font-size: 10px;
            padding-top: 2 !important;
            padding-bottom: 2 !important;
        }

        .table-tracking tbody td {
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
<body>
    <div>
        <div class="row">
            <div class="col-lg-12 text-center mb-3">
                <h4>Tracking Fabric Usage Report</h4>
            </div>
        </div>
        <table width="100%" class="table-tracking">
            <thead>
                <tr>
                    <!-- No -->
                    <th rowspan="2" width="5%">No.</th>
                    <th rowspan="2" width="10%">GL No.</th>
                    <th rowspan="2">Color</th>
                    <th rowspan="2" width="12%">Batch No.</th>
                    <th rowspan="2" width="6.5%">Roll</br>No.</th>
                    <th rowspan="2" width="6.5%">Sticker</br>Ydz</th>
                    <th colspan="3">Usage</th>
                    <th rowspan="2" width="6.5%">Balance End</th>
                </tr>
                <tr>
                    <th width="6.5%">Ydz</th>    
                    <th width="6.5%">Marker Length</th>    
                    <th width="6.5%">Layer</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cutting_order_record_details as $key => $item)
                    <tr>
                        <td style="text-align: center;">{{ $loop->iteration }}</td>
                        <td style="text-align: center;">{{ $item->gl_number }}</td>
                        <td>{{ $item->color }}</td>
                        <td>{{ $item->batch_number }}</td>
                        <td style="text-align: center;">{{ $item->roll_number }}</td>
                        <td style="text-align: right;">{{ $item->sticker_yardage }}</td>
                        <td style="text-align: right;">
                            @php
                                $actual_yardage = $item->marker_length * $item->layer;
                            @endphp
                            {{ $actual_yardage }}
                        <td style="text-align: right;">{{ $item->marker_length }}</td>
                        <td style="text-align: center;">{{ $item->layer }}</td>
                        <td style="text-align: right;">{{ $item->balance_end }}</td>
                        
                    </tr>
                @endforeach
                @php
                    $total_sticker_ydz = 0;
                    $total_usage_ydz = 0;
                    foreach ($cutting_order_record_details as $key => $item)
                    {
                        $total_sticker_ydz += $item->sticker_yardage;
                        $total_usage_ydz += $item->marker_length * $item->layer;
                    }
                @endphp
                <tr>
                    <td colspan="5" style="text-align: center;">Total</td>
                    <td style="text-align: center;">{{ $total_sticker_ydz }}</td>
                    <td style="text-align: center;">{{ $total_usage_ydz }}</td>
                    <td colspan="3"></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
