<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print PDF</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>
@php

@endphp
<body>
    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Color</th>
                <?php
                    $size = array();
                    foreach($cutting_order_record->layingPlanningDetail->layingPlanningDetailSize as $ct){
                        $size[] = $ct->size->size;
                    }
                    $size = array_unique($size);
                    foreach($size as $s){
                        echo "<th>$s</th>";
                    }
                ?>
                <th rowspan="2">Quantity</th>
            </tr>
            <tr>
                <?php
                    foreach($cutting_order_record->layingPlanningDetail->layingPlanningDetailSize as $ct){
                        echo "<th>$ct->ratio_per_size</th>";
                    }
                ?>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>{{ $cutting_order_record->layingPlanningDetail->layingPlanning->color->color }}</td>
                <?php
                    foreach($cutting_order_record->layingPlanningDetail->layingPlanningDetailSize as $ct){
                        echo "<td>$ct->qty_per_size</td>";
                    }
                ?>
                <td>{{ $cutting_order_record->layingPlanningDetail->total_all_size }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>