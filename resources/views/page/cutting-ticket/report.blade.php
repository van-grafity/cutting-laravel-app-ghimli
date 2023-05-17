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
            border: dotted 1.5px #000000;
        }

        .table-nota thead th {
            border: dotted 1.5px #000000;
            vertical-align: middle;
            font-size: 9pt;
            text-align: center;
        }
        .table-nota tbody td {
            border: dotted 1.5px #000000;
            font-weight: bold;
            height:25px;
            text-align: center;
        }
        
	</style>
</head>
<body>
    <div class="">
        <div class="header-main">
            <div class="company-name">
                Ghim Li Holdings Co. Pte Ltd
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
                    <!-- no, ticket_number -->
                    @foreach($data['array_size'] ?? [] as $cord)
                        @foreach($cord['data'] ?? [] as $item)
                        <tr>
                            <!-- no column -->
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