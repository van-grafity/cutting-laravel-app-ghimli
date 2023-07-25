<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <title>Print Pallets</title>
    <style type="text/css">
        @page {
            margin : 0.2cm;
        }

        @media print {
            html, body {
                width: 10.2cm;
                height: 6.4cm;
            }

            .page-break {
                page-break-after: always;
            }
        }

        .table-nota {
            border: 2px solid;
            width: 100%;
            height: 98.5%;
        }
    </style>
</head>
<body>
@foreach ($pallets as $pallet)
<div class="page-break">
<table class="table table-nota">
    <div style="text-align: center;">
        <h5 style="text-align: left; margin-left: 12px; font-size: 20px">PT. GHIM LI INDONESIA</h5>
        <img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl={{ $pallet->serial_number }}&choe=UTF-8" title="Link to Google.com" style="margin : 0 !important; padding : 0 !important;"/>
            <div class="serial-number" style="font-size: 32px; margin-top: 18px;"><b>{{ $pallet->serial_number }}</b></div>
    </div>
</table>
</div>
@endforeach
</body>
</html>