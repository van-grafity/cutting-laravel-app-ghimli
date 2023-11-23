<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cutting Order Status</title>
    <!-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    
    <style type="text/css">
        @page {
          margin-top: 1cm;
          margin-left: 0.4cm;
          margin-right: 0.4cm;
          margin-bottom: 1cm;
        }

        .table-nota td, .table-nota th {
          padding: 0.25rem 0.25rem;
          font-size: 7pt;
          vertical-align:middle;
        }

        .header-main { 
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .company-name {
            float: left;
            text-align: left;
            font-size: 12px;
        }

        .title-nota {
            clear:left;
            /* clear:right; */
            text-align: center;
            font-weight: bold;
            font-size: 14px;
        }
        
        .serial-number-qr {
            float:right;
            text-align: right;
            font-size: 12px;
        }
        .table-nota {
            border: 2px solid;
        }

        .table-nota thead th {
            border: 1px solid;
            vertical-align: middle;
            text-align: center;
            font-size: 9pt;
            margin: 0px !important;
            padding: 0px !important;
        }
        .table-nota tbody td {
            border: 1px solid;
            vertical-align: middle;
            text-align: center;
            font-weight: bold;
            font-size:8pt;
            padding-top: 1 !important;
            padding-bottom: 1 !important;
        }
        
	</style>
</head>

<body>
    <div class="">
        <div class="header-main">
            <div class="company-name">
                PT. GHIMLI INDONESIA
            </div>
            <div class="serial-number-qr">
                <div>RP-GLA-CUT-004</div>
                <div>Rev 0</div>
            </div>
            <div class="title-nota">
                STATUS CUTTING ORDER REPORT
                <br>
                @if ($data['gl_number'] == null)
                <div class="subtitle-nota"></div>
                @else
                <div class="subtitle-nota">GL : {{ $data['gl_number'] }}</div>
                @endif
                <div class="subtitle-nota"></div>
            </div>

        </div>

        <table class="table">
            <tbody>
                <tr>
                    <td style="font-size: 9pt; font-weight: bold; border: 0px solid !important; padding-top: 1.5 !important; padding-bottom: 1.5 !important; padding-left: 0.3 !important; padding-right: 0.3 !important;">Date Start</td>
                    <td style="font-size: 9pt; font-weight: bold; border: 0px solid !important; padding-top: 1.5 !important; padding-bottom: 1.5 !important; padding-left: 0.3 !important; padding-right: 0.3 !important;">:</td>
                    <td style="font-size: 9pt; font-weight: bold; border: 0px solid !important; padding-top: 1.5 !important; padding-bottom: 1.5 !important; padding-left: 0.3 !important; padding-right: 0.3 !important;">{{ $data['date_start'] }}</td>
                    <td style="font-size: 9pt; font-weight: bold; border: 0px solid !important; padding-top: 1.5 !important; padding-bottom: 1.5 !important; padding-left: 0.3 !important; padding-right: 0.3 !important;">Date End</td>
                    <td style="font-size: 9pt; font-weight: bold; border: 0px solid !important; padding-top: 1.5 !important; padding-bottom: 1.5 !important; padding-left: 0.3 !important; padding-right: 0.3 !important;">:</td>
                    <td style="font-size: 9pt; font-weight: bold; border: 0px solid !important; padding-top: 1.5 !important; padding-bottom: 1.5 !important; padding-left: 0.3 !important; padding-right: 0.3 !important;">{{ $data['date_end'] }}</td>
                </tr>
                @if ($data['status_layer'] == null || $data['status_layer'] == null)
                @else
                <tr>
                    @if ($data['status_layer'] == null)
                        <td style="font-size: 9pt; font-weight: bold; border: 0px solid !important; padding-top: 1.5 !important; padding-bottom: 1.5 !important; padding-left: 0.3 !important; padding-right: 0.3 !important;"></td>
                        <td style="font-size: 9pt; font-weight: bold; border: 0px solid !important; padding-top: 1.5 !important; padding-bottom: 1.5 !important; padding-left: 0.3 !important; padding-right: 0.3 !important;"></td>
                        <td style="font-size: 9pt; font-weight: bold; border: 0px solid !important; padding-top: 1.5 !important; padding-bottom: 1.5 !important; padding-left: 0.3 !important; padding-right: 0.3 !important;"></td>
                    @else
                        <td style="font-size: 9pt; font-weight: bold; border: 0px solid !important; padding-top: 1.5 !important; padding-bottom: 1.5 !important; padding-left: 0.3 !important; padding-right: 0.3 !important;">Status Layer</td>
                        <td style="font-size: 9pt; font-weight: bold; border: 0px solid !important; padding-top: 1.5 !important; padding-bottom: 1.5 !important; padding-left: 0.3 !important; padding-right: 0.3 !important;">:</td>
                        <td style="font-size: 9pt; font-weight: bold; border: 0px solid !important; padding-top: 1.5 !important; padding-bottom: 1.5 !important; padding-left: 0.3 !important; padding-right: 0.3 !important;">{{ $data['status_layer'] ?? '-' }}</td>
                    @endif
                    @if ($data['status_cut'] == null)
                        <td style="font-size: 9pt; font-weight: bold; border: 0px solid !important; padding-top: 1.5px !important; padding-bottom: 1.5px !important; padding-left: 0.3px !important; padding-right: 0.3px !important;"></td>
                        <td style="font-size: 9pt; font-weight: bold; border: 0px solid !important; padding-top: 1.5px !important; padding-bottom: 1.5px !important; padding-left: 0.3px !important; padding-right: 0.3px !important;"></td>
                        <td style="font-size: 9pt; font-weight: bold; border: 0px solid !important; padding-top: 1.5px !important; padding-bottom: 1.5px !important; padding-left: 0.3px !important; padding-right: 0.3px !important;"></td>
                    @else
                        <td style="font-size: 9pt; font-weight: bold; border: 0px solid !important; padding-top: 1.5 !important; padding-bottom: 1.5 !important; padding-left: 0.3 !important; padding-right: 0.3 !important;">Status Cut</td>
                        <td style="font-size: 9pt; font-weight: bold; border: 0px solid !important; padding-top: 1.5 !important; padding-bottom: 1.5 !important; padding-left: 0.3 !important; padding-right: 0.3 !important;">:</td>
                        <td style="font-size: 9pt; font-weight: bold; border: 0px solid !important; padding-top: 1.5 !important; padding-bottom: 1.5 !important; padding-left: 0.3 !important; padding-right: 0.3 !important;">{{ $data['status_cut'] ?? '-' }}</td>
                    @endif
                </tr>
                @endif
            </tbody>
        </table>
            <div class="body-nota">
                <table class="table table-nota">
                    <thead class="">
                        <tr>
                            <th width="0.5%">No</th>
                            @if ($data['gl_number'] == null)
                            <th width="5%">GL</th>
                            @endif
                            <th>Serial Number</th>
                            <th>Color</th>
                            <th width="8%">Style</th>
                            <th width="3%">MI Qty</th>
                            <th width="3%">Cut Qty</th>
                            @if ($data['status_layer'] == null || $data['status_layer'] == null)
                                <th width="7%">Status Layer</th>
                                <th width="7.5%">Status Cut</th>
                                <th width="7%">Date Layer</th>
                                <th width="7%">Date Cut</th>
                            @else
                                <th width="7%">Status Layer</th>
                                <th width="7.5%">Status Cut</th>
                                <th width="7%">Date Layer</th>
                                <th width="7%">Date Cut</th>
                            @endif
                            
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($data['cuttingOrderRecord'] as $item => $value)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            @if ($data['gl_number'] == null)
                            <td>{{ $value->layingPlanningDetail->layingPlanning->gl->gl_number }}</td>
                            @endif
                            <td style="text-align: left;">{{ $value->serial_number }}</td>
                            <td style="text-align: left;">{{ $value->layingPlanningDetail->layingPlanning->color->color }}</td>
                            <td style="text-align: left;">{{ $value->layingPlanningDetail->layingPlanning->style->style }}</td>
                            <td>{{ $value->layingPlanningDetail->layingPlanning->order_qty }}</td>
                            <td>
                                <?php
                                    $total_cutting_order_record = 0;
                                    $total_size_ratio = 0;
                                    foreach ($value->cuttingOrderRecordDetail as $record_detail)
                                    {
                                        $total_cutting_order_record += $record_detail->layer;
                                    }
                                    foreach ($value->layingPlanningDetail->layingPlanningDetailSize as $size)
                                    {
                                        $total_size_ratio += $size->ratio_per_size;
                                    }
                                    echo ($total_cutting_order_record * $total_size_ratio) == 0 ? '-' : $total_cutting_order_record * $total_size_ratio;
                                ?>
                            </td>
                            <td>
                                @if ($value->statusLayer->name == 'not completed')
                                    Belum Layer
                                @elseif ($value->statusLayer->name == 'completed')
                                    Sudah Layer
                                @else
                                    Over Layer
                                @endif
                            </td>
                            <td>
                                @if ($value->statusCut->name == 'belum')
                                    Belum Potong
                                @else
                                    Sudah Potong
                                @endif
                            </td>
                            <td><?php
                                foreach ($value->cuttingOrderRecordDetail as $detail) {
                                    if ($detail != null) {
                                        echo date('d/M/Y', strtotime($detail->updated_at));
                                        break;
                                    }
                                }
                            ?></td>
                            <td><?php
                                foreach ($value->cuttingOrderRecordDetail as $detail) {
                                    if ($detail != null) {
                                        echo date('d/M/Y', strtotime($value->updated_at));
                                        break;
                                    }
                                }
                            ?></td>
                        </tr>
                        @if ($item == count($data['cuttingOrderRecord']) - 1)
                        @if ($data['gl_number'] == null)
                        @if ($value->layingPlanningDetail->layingPlanning->color->color == $data['cuttingOrderRecord'][$item - 1]->layingPlanningDetail->layingPlanning->color->color)
                                    <tr style="background-color: #f2f2f2;">
                                        @if ($data['gl_number'] == null)
                                        <td colspan="5" style="text-align: right;">Sub Total</td>
                                        @else
                                        <td colspan="4" style="text-align: right;">Sub Total</td>
                                        @endif
                                        <td style="text-align: center;">
                                            <?php
                                            $sum = 0;
                                            foreach ($data['cuttingOrderRecord'] as $item)
                                            {
                                                $total_cutting_order_record = 0;
                                                $total_size_ratio = 0;
                                                foreach ($item->cuttingOrderRecordDetail as $record_detail)
                                                {
                                                    if ($item->layingPlanningDetail->layingPlanning->color->color == $value->layingPlanningDetail->layingPlanning->color->color)
                                                    {
                                                        $total_cutting_order_record += $record_detail->layer;
                                                    }
                                                }
                                                foreach ($item->layingPlanningDetail->layingPlanningDetailSize as $size)
                                                {
                                                    $total_size_ratio += $size->ratio_per_size;
                                                }
                                                $sum += ($total_cutting_order_record * $total_size_ratio);
                                            }
                                            echo $sum == 0 ? '-' : $sum;
                                            ?>
                                        </td>
                                        <td colspan="4"></td>
                                    </tr>
                                @endif
                        @else
                                
                                @endif
                        @else
                            @if ($value->layingPlanningDetail->layingPlanning->color->color == $data['cuttingOrderRecord'][$item + 1]->layingPlanningDetail->layingPlanning->color->color)
                            @else
                            @if ($data['gl_number'] == null)
                            <tr style="background-color: #f0f0f0;">
                                    @if ($data['gl_number'] == null)
                                    <td colspan="6" style="text-align: right;">Sub Total</td>
                                    @else
                                    <td colspan="5" style="text-align: right;">Sub Total</td>
                                    @endif
                                    <td style="text-align: center;">
                                        <?php
                                        $sum = 0;
                                        foreach ($data['cuttingOrderRecord'] as $item)
                                        {
                                            $total_cutting_order_record = 0;
                                            $total_size_ratio = 0;
                                            foreach ($item->cuttingOrderRecordDetail as $record_detail)
                                            {
                                                if ($item->layingPlanningDetail->layingPlanning->color->color == $value->layingPlanningDetail->layingPlanning->color->color)
                                                {
                                                    $total_cutting_order_record += $record_detail->layer;
                                                }
                                            }
                                            foreach ($item->layingPlanningDetail->layingPlanningDetailSize as $size)
                                            {
                                                $total_size_ratio += $size->ratio_per_size;
                                            }
                                            $sum += ($total_cutting_order_record * $total_size_ratio);
                                        }
                                        echo $sum == 0 ? '-' : $sum;
                                        ?>
                                    </td>
                                    
                                    @if ($data['gl_number'] == null)
                                        <td colspan="4"></td>
                                    @else
                                        <td colspan="4"></td>
                                    @endif
                                </tr>
                            @else
                                
                            @endif
                            @endif
                        @endif
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background-color: #d9d9d9;">
                            @if ($data['gl_number'] == null)
                            <th colspan="6" style="text-align: right;">Total</th>
                            @else
                            <th colspan="5" style="text-align: right;">Total</th>
                            @endif
                            <th style="text-align: center;">
                                <?php
                                $sum = 0;
                                foreach ($data['cuttingOrderRecord'] as $item)
                                {
                                    $total_cutting_order_record = 0;
                                    $total_size_ratio = 0;
                                    foreach ($item->cuttingOrderRecordDetail as $record_detail)
                                    {
                                        $total_cutting_order_record += $record_detail->layer;
                                    }
                                    foreach ($item->layingPlanningDetail->layingPlanningDetailSize as $size)
                                    {
                                        $total_size_ratio += $size->ratio_per_size;
                                    }
                                    $sum += ($total_cutting_order_record * $total_size_ratio);
                                }
                                echo $sum == 0 ? '-' : $sum;
                                ?>
                            </th>
                            <!-- <th colspan="5"></th> -->
                            @if ($data['gl_number'] == null)
                                <th colspan="4"></th>
                            @else
                                <th colspan="4"></th>
                            @endif
                        </tr>
                    </tfoot>
                </table>
            </div>
    </div>
</body>
</html>