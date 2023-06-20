<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cutting Order Record</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <style type="text/css">
        @page {
            margin-top: 1cm;
            margin-left: 1cm;
            margin-bottom: 0cm;
        }

		table tr td,
		table tr th{
			font-size: 8pt;
		}

        .table-nota td, .table-nota th {
            padding: 0.25rem 0.25rem;
			font-size: 7pt;
            /* text-align:center; */
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
        
        .subtitle-nota {
            font-weight: Normal;
            font-size: 11px;
        }

        .serial-number-qr {
            float:right;
            text-align: right;
            font-size: 12px;
        }

        .header-subtitle {
            font-weight: bold;
            width: 100%;
            margin-bottom: .5rem;
        }

        .header-subtitle td {
            vertical-align: bottom;
            border-bottom: 1px solid;
            font-size:12px;
        }
        .header-subtitle td.no-border {
            border: none;
        }

        .subtitle-right {
            /* text-align: right */
        }

        .table-nota {
            border: 2px solid;
        }

        .table-nota thead th {
            border: 1px solid;
            vertical-align: middle;
            font-size: 9pt;
        }
        .table-nota tbody td {
            border: 1px solid;
            font-weight: bold;
            height:25px;
            /* font-size:8pt; */
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
                <!-- <div class="qr-wrapper" style="margin-top: -15px; margin-right: -15px;">
                    <img src="https://chart.googleapis.com/chart?chs=70x70&cht=qr&chl=123456" alt="">
                </div> -->
            </div>
            <div class="title-nota">
                DAILY CUTTING OUTPUT REPORT
                <br>
                <div class="subtitle-nota"></div>
            </div>

        </div>
        <table class="header-subtitle">
            <thead>
                <tr>
                    <td class="no-border"></td>
                </tr>
            </thead>
        </table>
        <div class="body-nota">
            <table class="table table-nota">
                <thead class="">
                    <tr>
                        <th rowspan="2" >BUYER</th>
                        <th rowspan="2" >STYLE#</th>
                        <th rowspan="2" >GL#</th>
                        <th rowspan="2" >COLOR</th>
                        <th rowspan="2" >MI QTY</th>
                        <th rowspan="1" >Date</th>
                        <th rowspan="1" >{{ $date_filter }}</th>
                        <th rowspan="2" >Total Qty per Day</th>
                        <th rowspan="2" >Accumulation</th>
                        <th rowspan="2" >Completed (%)</th>
                    </tr>
                    <tr>
                        <th colspan="2" >Previous Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data_daily_cutting as $key => $data_daily)
                    <tr>
                        <td colspan="1">{{ $data_daily->layingPlanningDetail->layingPlanning->gl->buyer->name }}</td>
                        <td colspan="1">{{ $data_daily->layingPlanningDetail->layingPlanning->style->style }}</td>
                        <td colspan="1">{{ $data_daily->layingPlanningDetail->layingPlanning->gl->gl_number }}</td>
                        <td colspan="1">{{ $data_daily->layingPlanningDetail->layingPlanning->color->color }} <?php
                            $table_number = $data_daily->layingPlanningDetail->table_number;
                            $table_number = str_pad($table_number, 3, '0', STR_PAD_LEFT);
                            echo $table_number;
                        ?></td>
                        <td colspan="1">{{ $data_daily->layingPlanningDetail->layingPlanning->order_qty }}</td>
                        <td colspan="2"></td>
                        <td colspan="1"><?php
                            $total_ratio = 0;
                            $total_layer = 0;
                            foreach ($data_daily->layingPlanningDetail->layingPlanningDetailSize as $key => $size) {
                                $total_ratio += $size->ratio_per_size;
                            }
                            foreach ($data_daily->cuttingOrderRecordDetail as $key => $detail) {
                                $total_layer += $detail->layer;
                            }
                            echo $total_ratio * $total_layer;
                        ?></td>
                        <td colspan="1"><?php
                            $total_ratio = 0;
                            $total_layer = 0;
                            foreach ($data_daily->layingPlanningDetail->layingPlanningDetailSize as $key => $size) {
                                $total_ratio += $size->ratio_per_size;
                            }
                            foreach ($data_daily->cuttingOrderRecordDetail as $key => $detail) {
                                $total_layer += $detail->layer;
                            }
                            echo $total_ratio * $total_layer;
                        ?></td>
                        <td colspan="1"><?php
                            $total_ratio = 0;
                            $total_layer = 0;
                            foreach ($data_daily->layingPlanningDetail->layingPlanningDetailSize as $key => $size) {
                                $total_ratio += $size->ratio_per_size;
                            }
                            foreach ($data_daily->cuttingOrderRecordDetail as $key => $detail) {
                                $total_layer += $detail->layer;
                            }
                            echo round(($total_ratio * $total_layer) / $data_daily->layingPlanningDetail->layingPlanning->order_qty * 100, 2) . '%';
                        ?></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>    
        </div>
    </div>
</body>
</html>