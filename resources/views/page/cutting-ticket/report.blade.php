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
                    foreach($cutting_tickets as $ct){
                        $size[] = $ct->size->size;
                    }
                    $size = array_unique($size);
                    foreach($size as $s){
                        echo "<th>$s</th>";
                    }
                ?>
                <th scope="col">Quantity</th>
            </tr>
            <tr>
                <?php
                $layer = array();
                foreach($cutting_tickets as $ct){
                    $layer[] = $ct->layer;
                }
                $layer = array_unique($layer);
                foreach($layer as $l){
                    echo "<th>$l</th>";
                }
                ?>
                <th scope="col"> - </th>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
    </table>
</body>
</html>