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
                </td>
            </tr>
        </table>
        <br/>
        <!--$pdf = PDF::loadView('page.subcon.report2', compact('cuttingOrderRecord', 'cuttingOrderRecordDetail', 'detail'))->setPaper('a4', 'potrait'); -->
        <br/>
        <table class="table table-bordered" style="width:100%; font-size: 10px; font-weight: bold; margin-bottom: 0 !important; padding-bottom: 28 !important;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No</br>Laying</br>Sheet</th>
                    <th>Date</th>
                    <th>COR Serial No.</th>
                    <th>Color</th>
                    <th>Layer</th>
                    <th>Pcs</th>
                    <th>Ratio</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($details as $key => $value)
                    @foreach ($cuttingOrderRecord as $key2 => $value2)
                        @if ($value->id == $value2->laying_planning_detail_id)
                        
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $value->no_laying_sheet }}</td>
                                <td>{{ date('d-m-Y', strtotime($value2->updated_at)) }}</td>
                                <td>{{ $value2->serial_number }}</td>
                                <td>{{ $value->layingPlanning->color->color }}</td>
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
                                <td>
                                    @foreach ($value->layingPlanningDetailSize as $size)
                                        @if ($size->qty_per_size == 0)
                                            <span>-</span>
                                        @else
                                            <span>{{ $size->ratio_per_size }}</span>
                                        @endif
                                    @endforeach
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
                        <span>ROBERT (36120)</span>
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