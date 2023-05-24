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

		table.table-bordered > thead > tr > th{
            border-top: 1px dotted black;
            border-bottom: 1px dotted black;
            border-left: 1px dotted black;
            border-right: 1px dotted black;
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

        .table thead th {
            text-align: center;
            vertical-align: middle;
            font-size: 8px;
            padding-top: 2 !important;
            padding-bottom: 2 !important;
        }
        
        .table tbody td {
            border: 1px dotted black;
            text-align: center;
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
<body>
    <div class="">
        <div class="header-main">
            <div class="company-name">
                Ghim Li Indonesia
                <br>
                Date: {{ date('d/m/Y') }}
                <br>
                </br>
                PAKING LIST
                <br>
                Style# : {{ $data['cutting_order_record']->layingPlanningDetail->layingPlanning->style->style }}
                <br>
                Job/PO# : {{ $cutting_order_record->layingPlanningDetail->layingPlanning->gl->gl_number }}
            </div>
            <div>
                <br>
                <br>
                <br>
                <br>
            </div>

        </div>
        <div>
            <table class="table table-bordered">
                <thead class="">
                    <tr>
                        <th rowspan="3">No</th>
                        <th rowspan="3">Color</th>
                        <?php
                            $size = array();
                            foreach($cutting_order_record->layingPlanningDetail->layingPlanningDetailSize ?? [] as $ct){
                                $size[] = $ct->size->size;
                            }
                            $size = array_unique($size);
                            foreach($size as $s){
                                echo "<th colspan='2'>$s</th>";
                            }
                        ?>
                    </tr>
                    <tr>
                        <?php
                            foreach($cutting_order_record->layingPlanningDetail->layingPlanningDetailSize ?? [] as $ct){
                                echo "<th colspan='2'>$ct->ratio_per_size</th>";
                            }
                        ?>
                    </tr>
                    <tr>
                        <?php
                            foreach($cutting_order_record->layingPlanningDetail->layingPlanningDetailSize ?? [] as $ct){
                                echo "<th colspan='2'>Bund/Qty</th>";
                            }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['array_size'] ?? [] as $cord)
                        @foreach($cord['data'] ?? [] as $item)
                        <tr>
                            <td>{{ $item->no }}</td>
                            <td>{{ $item->color }}</td>
                            <?php
                            foreach($cutting_order_record->layingPlanningDetail->layingPlanningDetailSize ?? [] as $ct){
                                echo "<td>CT00$item->no</td>
                                <td>$item->qty</td>";
                            }
                        ?>
                        </tr>
                        @endforeach
                    @endforeach
            </table>
        </div>
    </div>
</body>
</html>