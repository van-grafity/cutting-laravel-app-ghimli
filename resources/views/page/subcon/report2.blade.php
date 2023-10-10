<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUMMARY CUTTING BY GROUP</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">


</head>
<body>
    <div>
        <table width="100%">
            <tr>
                <td colspan="2" style="text-align: center; font-weight: bold; font-size: 14px;">
                    SUMMARY CUTTING {{ strtoupper($cuttingOrderRecordDetail[0]->user->name) }}
                    <br/>
                    <span style="font-size: 12px;">{{ $date_start }} - {{ $date_end }}</span>
                </td>
            </tr>
        </table>
        <br/>
        <br/>

        @php
            $size_all = [];
            foreach ($details as $key => $value)
            {
                foreach ($value->layingPlanningDetailSize as $size)
                {
                    if (!in_array($size->size->size, $size_all))
                    {
                        array_push($size_all, $size->size->size);
                    }
                }
            }

            $layer = 0;
            foreach ($cuttingOrderRecord as $record)
            {
                if ($record->laying_planning_detail_id == $value->id)
                {
                    foreach ($record->cuttingOrderRecordDetail as $record_detail)
                    {
                        $layer += $record_detail->layer;
                    }
                }
            }
        @endphp
        
        <table class="table table-bordered" style="width:100%; font-size: 10px; font-weight: bold; margin-bottom: 0 !important; padding-bottom: 28 !important;">
            <thead>
                <tr>
                    <th rowspan="2" width="2%">No</th>
                    <th rowspan="2">COR Serial No.</th>
                    <th rowspan="2" width="6.5%">No</br>Laying</br>Sheet</th>
                    <th rowspan="2" width="6.2%">Date</th>
                    <th rowspan="2">Color</th>
                    <th rowspan="2">Size/Ratio</th>
                    <th colspan="{{ count($size_all) }}">Size/Ratio</th>
                    <th rowspan="2" width="3.2%">Layer</th>
                    <th rowspan="2" width="3.6%">Pcs</th>
                    <th rowspan="2" width="3.2%">Dz</th>
                </tr>
                <tr>
                    @foreach ($size_all as $size)
                        <th rowspan="1" width="2%">{{ $size }}</th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @foreach ($details as $key => $value)
                    @foreach ($cuttingOrderRecord as $key2 => $value2)
                        @if ($value->id == $value2->laying_planning_detail_id)
                        
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td style="text-align: left; padding-left: 4px !important;">{{ $value2->serial_number }}</td>
                                <td>{{ $value->no_laying_sheet }}</td>
                                <td>{{ date('d-m-Y', strtotime($value2->updated_at)) }}</td>
                                
                                <td style="text-align: left; padding-left: 4px !important;">{{ $value->layingPlanning->color->color }}</td>
                                <!-- size ratio 1 -->
                                <td>
                                    @foreach ($value->layingPlanningDetailSize as $size)
                                            <span>{{ $size->size->size . '=' . $size->ratio_per_size }}</span>
                                    @endforeach
                                </td>
                                <!-- size ratio 2 -->
                                @foreach ($size_all as $size)
                                    <td><?php
                                        $ratio = 0;
                                        foreach ($value->layingPlanningDetailSize as $size2)
                                        {
                                            if ($size2->size->size == $size)
                                            {
                                                $ratio = $size2->ratio_per_size;
                                            }
                                        }
                                        echo $ratio == 0 ? '-' : $ratio;
                                    ?></td>
                                @endforeach
                                <td><?php
                                $total_cutting_order_record = 0;
                                foreach ($cuttingOrderRecord as $record)
                                {
                                    if ($record->laying_planning_detail_id == $value->id)
                                    {
                                        foreach ($record->cuttingOrderRecordDetail as $record_detail)
                                        {
                                            $total_cutting_order_record += $record_detail->layer;
                                        }
                                    }
                                }
                                echo $total_cutting_order_record;
                                ?></td>
                                <td><?php
                                    $total_cutting_order_record = 0;
                                    $total_size_ratio = 0;
                                    foreach ($cuttingOrderRecord as $record)
                                    {
                                        if ($record->laying_planning_detail_id == $value->id)
                                        {
                                            foreach ($record->cuttingOrderRecordDetail as $record_detail)
                                            {
                                                $total_cutting_order_record += $record_detail->layer;
                                            }
                                        }
                                    }
                                    foreach ($value->layingPlanningDetailSize as $size)
                                    {
                                        $total_size_ratio += $size->ratio_per_size;
                                    }
                                    echo ($total_cutting_order_record * $total_size_ratio) == 0 ? '-' : $total_cutting_order_record * $total_size_ratio;
                                ?>
                                </td>
                                <td><?php
                                    $total_cutting_order_record = 0;
                                    $total_size_ratio = 0;
                                    foreach ($cuttingOrderRecord as $record)
                                    {
                                        if ($record->laying_planning_detail_id == $value->id)
                                        {
                                            foreach ($record->cuttingOrderRecordDetail as $record_detail)
                                            {
                                                $total_cutting_order_record += $record_detail->layer;
                                            }
                                        }
                                    }
                                    foreach ($value->layingPlanningDetailSize as $size)
                                    {
                                        $total_size_ratio += $size->ratio_per_size;
                                    }
                                    $res = ($total_cutting_order_record * $total_size_ratio) / 12;
                                    $res = number_format((float)$res, 2, '.', '');
                                    echo ($total_cutting_order_record * $total_size_ratio) == 0 ? '-' : $res;
                                ?>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @endforeach
            </tbody>
        </table>

        <br>
        <br>

        <table width="100%" style="font-size: 12px; font-family: Times New Roman, Times, serif; font-weight: bold; position: absolute; bottom: 60px;">
            <tr>
                <td width="50%" style="text-align: center;">
                    <p>Prepared by:</p>
                    <p></p>
                    <div style="display:inline-block; text-align:center;">
                        <span>MELDA (58734)</span>
                        <hr style="border: none; height: 1px; background-color: black; margin-top: 0px; margin-bottom: 0px; width: 90px;">
                    </div>
                </td>
                <td width="50%" style="text-align: center;">
                    <p>Authorized by:</p>
                    <p></p>
                    <div style="display:inline-block; text-align:center;">
                        <span>SUMIYATI (54057)</span>
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

    table.table-bordered > thead > tr > th{
        border-top: 1px solid black;
        border-bottom: 1px solid black;
        border-left: 1px solid black;
        border-right: 1px solid black;
    }

    .table thead th {
        text-align: center;
        vertical-align: middle;
        font-size: 10px;
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