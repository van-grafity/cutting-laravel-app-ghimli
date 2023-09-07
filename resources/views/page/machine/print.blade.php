<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print PDF</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <style type="text/css">
        @page {
            margin-top: 0.5cm;
            margin-left: 1.2cm;
            margin-bottom: 0cm;
        }
        .detail-ticket {
            font-size: 7.5pt;
            font-weight: 700;
            text-align: center;
            vertical-align: middle;
            
        }

        .serial-number {
            font-size: 8.5pt;
            font-weight: 700;
        }
        .page {
            page-break-after: always;
        }
        .page:last-child {
            page-break-after: unset;
        }
	</style>
</head>

<body>
    <table class="detail-ticket">
        <tbody>
            <tr>
                <td rowspan="5" style="padding: 0px; margin: 0px;" width="100">
                
                <img src="data:image/png;base64, {!! base64_encode(QrCode::size(160)->generate($data['serial_number'])) !!} ">
                </td>
            </tr>
            <tr>
                <td rowspan="3" width="100" height="100" style="padding-left: 12px; margin: 0px; font-size: 24px;">
                    {{ $data['serial_number'] }}
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>