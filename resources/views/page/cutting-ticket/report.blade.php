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
                Job/PO# : {{ $data['cutting_order_record']->layingPlanningDetail->layingPlanning->gl->gl_number }}
            </div>
            <div>
                <br>
                <br>
                <br>
                <br>
            </div>

        </div>
        <div>
            </br>
            <table class="table table-bordered">
                <thead class="">
                <tr>
                        <th>No</th>
                        <th>Color</th>
                        <th>Size</th>
                        <th>Bundle</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($data['cutting_order_record']->cuttingTicket as $ct)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $data['color']->color }}</td>
                        <td>{{ $ct->size->size }}</td>
                        <td>{{ $ct->ticket_number }}</td>
                    </tr>
                @endforeach
            </table>

            <table class="table table-bordered">
                <thead class="">
                    <tr>
                        <th>Color</th>
                        @foreach($data['cutting_order_record']->layingPlanningDetail->layingPlanningDetailSize as $ct)
                            <th>{{ $ct->size->size }}</th>
                        @endforeach
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $data['cutting_order_record']->layingPlanningDetail->layingPlanning->color->color }}</td>
                        @foreach($data['cutting_order_record']->layingPlanningDetail->layingPlanningDetailSize as $ct)
                            <td>{{ $ct->ratio_per_size * $ct->qty_per_size }}</td>
                        @endforeach
                        <td><?php 
                            $total = 0;
                            foreach($data['cutting_order_record']->layingPlanningDetail->layingPlanningDetailSize as $ct){
                                $total += $ct->ratio_per_size * $ct->qty_per_size;
                            }
                            echo $total;
                        ?></td>
                    </tr>
                    <tr>
                        <td>Total</td>
                        @foreach($data['cutting_order_record']->layingPlanningDetail->layingPlanningDetailSize as $ct)
                            <td>{{ $ct->ratio_per_size * $ct->qty_per_size }}</td>
                        @endforeach
                        <td><?php 
                            $total = 0;
                            foreach($data['cutting_order_record']->layingPlanningDetail->layingPlanningDetailSize as $ct){
                                $total += $ct->ratio_per_size * $ct->qty_per_size;
                            }
                            echo $total;
                        ?></td>
                    </tr>
                </tbody>
            </table>

            
        </div>
    </body>
</html>

<!-- <table class="table table-bordered">
    <thead class="">
        <tr>
            <th rowspan="3">No</th>
            <th rowspan="3">Color</th>
            @foreach($data['cutting_order_record']->layingPlanningDetail->layingPlanningDetailSize as $ct)
                <th colspan="2">{{ $ct->size->size }}</th>
            @endforeach
        </tr>
        <tr>
            <?php
                foreach($data['cutting_order_record']->layingPlanningDetail->layingPlanningDetailSize ?? [] as $ct){
                    echo "<th colspan='2'>$ct->ratio_per_size</th>";
                }
            ?>
        </tr>
        <tr>
            <?php
                foreach($data['cutting_order_record']->layingPlanningDetail->layingPlanningDetailSize ?? [] as $ct){
                    echo "<th colspan='2'>Ticket/Qty</th>";
                }
            ?>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table> -->