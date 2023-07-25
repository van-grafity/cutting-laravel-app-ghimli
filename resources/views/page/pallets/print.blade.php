<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <title>Print Pallets</title>
    <style type="text/css">
        @page {
            size: 10.2cm 6.4cm;
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
            height: 97%;
        }
    </style>
</head>
<body>
@foreach ($pallets as $pallet)
<div class="page-break">
<table class="table table-nota">
    <div style="text-align: center;">
        <!-- QrCode::size(300)->generate('A basic example of QR code!'); generate serial number -->
        <!-- float top left pt ghim li -->
        <h5 style="text-align: left; margin-left: 8px;">PT. GHIM LI INDONESIA</h5>
        <img src="https://chart.googleapis.com/chart?chs=125x125&cht=qr&chl={{ $pallet->serial_number }}&choe=UTF-8" title="Link to Google.com"  style="margin: 0; display: block; width: 100px; height: 100px;"/>
            <div class="serial-number">{{ $pallet->serial_number }}</div>
    </div>
</table>
</div>
@endforeach
</body>
</html>