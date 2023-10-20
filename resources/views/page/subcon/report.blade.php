<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAYING PLANNING & CUTTING REPORT</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">


</head>
<body>
    <div>
        <table width="100%">
            <tr>
                <td colspan="2" style="text-align: center; font-weight: bold; font-size: 14px;">
                    SUMMARY CUTTING REPORT SUBCON
                </td>
            </tr>
        </table>
        <br/>
        <table width="100%" style="font-size: 10px; font-weight: bold; padding-top: 2 !important; padding-bottom: 2 !important; padding-left: 4 !important; padding-right: 4 !important;">
            <tr>
                <td width="6%">Buyer</td>
                <td>{{ $data->buyer->name }}</td>
            </tr>

            <tr>
                <td width="6%">Style</td>
                <td>{{ $data->style->style }}</td>
            </tr>
            <tr>
                <td width="6%">GL</td>
                <td>{{ $data->gl->gl_number }}</td>
            </tr>

            <tr>
                <td width="6%">Color</td>
                <td>{{ $data->color->color }}</td>
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
            
        @endphp
        <table class="table table-bordered" style="width:100%; font-size: 10px; font-weight: bold; margin-bottom: 0 !important; padding-bottom: 28 !important;">
            <thead>
                <tr>
                    <th rowspan="3">No</th>
                    <th rowspan="3" width="10%">No</br>Laying</br>Sheet</th>
                    <th rowspan="3">Date</th>
                    <th colspan="{{ $length }}">Ratio</th>
                    <th rowspan="3">Lay</br>Qty</th>
                    <th rowspan="3">Color</th>
                    <th rowspan="3">Cut</br>Qty</th>
                </tr>
                <tr>
                    @foreach ($data->layingPlanningSize as $item)
                    <th>{{ $item->size->size }}</th>
                    @endforeach
                </tr>
                <tr>
                    @foreach ($data->layingPlanningSize as $item)
                    <th>{{ $item->quantity }}</th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @foreach ($details as $detail)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $detail->no_laying_sheet }}</td>
                    <td>{{ date('d-M', strtotime($detail->layingPlanning->plan_date)) }}</td>
                    @foreach ($detail->layingPlanningDetailSize as $item)
                        @if ($item->qty_per_size == 0)
                            <td>-</td>
                        @else
                            <td>{{ $item->ratio_per_size }}</td>
                        @endif
                    @endforeach
                    <td>{{ $detail->layer_qty }}</td>
                    <td>{{ $detail->layingPlanning->color->color }}</td>
                    <td>
                        <?php
                         $total_cutting_order_record = 0;
                         $total_size_ratio = 0;
                         foreach ($cuttingOrderRecord as $record)
                         {
                             if ($record->laying_planning_detail_id == $detail->id)
                             {
                                 foreach ($record->cuttingOrderRecordDetail as $record_detail)
                                 {
                                     $total_cutting_order_record += $record_detail->layer;
                                 }
                             }
                         }
                        foreach ($detail->layingPlanningDetailSize as $size)
                        {
                            $total_size_ratio += $size->ratio_per_size;
                        }
                        echo ($total_cutting_order_record * $total_size_ratio) == 0 ? '-' : $total_cutting_order_record * $total_size_ratio;
                        ?>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <br>
        <br>

        <table width="100%" style="font-size: 10px; font-weight: bold;" hidden>
            <tr>
                <td width="20%">Total Layer Qty base on Marker Code</td>
                <td width="60%" colspan="7">: <?php
                    $marker_code = [];
                    $total_layer = 0;
                    foreach ($details as $detail)
                    {
                        array_push($marker_code, $detail->marker_code);
                    }
                    $marker_code = array_unique($marker_code);
                    $marker_code = array_values($marker_code);
                    foreach ($marker_code as $code)
                    {
                        foreach ($details as $detail)
                        {
                            if ($code == $detail->marker_code)
                            {
                                $total_layer += $detail->layer_qty;
                            }
                        }
                        echo $code . ' = ' . $total_layer . ' ';
                        $total_layer = 0;
                    }
                ?></td>
            </tr>
        </table>
        
        <table width="100%" style="font-size: 12px; font-family: Times New Roman, Times, serif; font-weight: bold; position: absolute; bottom: 60px;">
            <tr>
                <td width="50%" style="text-align: center;">
                    <p>Prepared by:</p>
                    <p></p>
                    <div style="display:inline-block; text-align:center;">
                        <div style="height: 18px;"></div>
                        <hr style="border: none; height: 1px; background-color: black; margin-top: 0px; margin-bottom: 0px; width: 90px;">
                    </div>
                </td>
                <td width="50%" style="text-align: center;">
                    <p>Authorized by:</p>
                    <p></p>
                    <div style="display:inline-block; text-align:center;">
                        <div style="height: 18px;"></div>
                        <hr style="border: none; height: 1px; background-color: black; margin-top: 0px; margin-bottom: 0px; width: 90px;">
                    </div>
                </td>
                <td width="50%" style="text-align: center;">
                    <p>Approved by:</p>
                    <p></p>
                    <div style="display:inline-block; text-align:center;">
                        <div style="height: 18px;"></div>
                        <hr style="border: none; height: 1px; background-color: black; margin-top: 0px; margin-bottom: 0px; width: 90px;">
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>

<style type="text/css">
    * {
        font-family: Calibri, san-serif;
    }
    
    /* @page {
        margin-top: 1cm;
        margin-left: 0.4cm;
        margin-right: 0.4cm;
        margin-bottom: 3.5cm;
    } */
    
    table.table-bordered > thead > tr > th{
        border-top: 1px solid black;
        border-bottom: 1px solid black;
        border-left: 1px solid black;
        border-right: 1px solid black;
    }

    .table thead th {
        text-align: center;
        vertical-align: middle;
        font-size: 8px;
        padding-top: 1 !important;
        padding-bottom: 1 !important;
        padding-left: 0.3 !important;
        padding-right: 0.3 !important;
    }
    .table tbody td {
        border: 1px solid;
        text-align: center;
        vertical-align: middle;
        font-weight: bold;
        font-size: 10px;
        padding-top: 1.5 !important;
        padding-bottom: 1.5 !important;
        padding-left: 0.3 !important;
        padding-right: 0.3 !important;
        margin-bottom: 0px !important;
    }
</style>