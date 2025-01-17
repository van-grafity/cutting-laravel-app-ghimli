<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print PDF</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style type="text/css">
        @page {
            size: 10.16cm 6.35cm;
            margin-left: 0.5cm;
            margin-right: 0.5cm;
            margin-top: 0.2cm;
            margin-bottom: 0.2cm;
        }
        body {
            width: 100%;
            height: 100%;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
            font-size: 10px;
        }

        table tbody tr {
            width: 100%;
            text-align: center;
            vertical-align: middle;
        }

        serial-number {
            font-size: 8px;
        }
	</style>
</head>

<body width="100%" height="100%">
    @foreach ($data as $cutting_ticket)
        </br>
        <div class="row">
            <div class="serial-number" style="text-align: center; font-size: 11px;">Serial No. : <span style="font-size: 12px;">{{ $cutting_ticket->serial_number }}</span></div>
            <div class="serial-number" style="text-align: center; font-size: 11px;">Color : <span style="font-size: 12px;">{{ $cutting_ticket->color }}</span></div>
            <div class="serial-number" style="text-align: center; font-size: 11px;">Style : <span style="font-size: 12px;">{{ $cutting_ticket->style }}</span></div>
        </div>
        </br>
        <table style="margin:0 auto;">
            <tbody>
                <tr>
                    <td rowspan="4" style="text-align: center; font-size: 12px;">
                        <img src="data:image/png;base64, {!! base64_encode(QrCode::size(120)->generate($cutting_ticket->serial_number)) !!} ">
                    </td>
                    <td width="14px"></td>
                    <td width="14%" style="text-align: left; font-size: 14px;">Ticket No. </td>
                    <td style="padding-left: 8px; padding-right: 8px; font-size: 14px;">: </td>
                    <td style="text-align: left; font-size: 14px;">{{ $cutting_ticket->ticket_number }} </td>
                </tr>
                <tr>
                    <td></td>
                    <td width="14%" style="text-align: left; font-size: 14px;">Buyer </td>
                    <td style="padding-left: 8px; padding-right: 8px; font-size: 14px;">: </td>
                    <td style="text-align: left; font-size: 14px;">{{ Str::limit($cutting_ticket->buyer, 15) }} </td>
                </tr>
                <tr>
                    <td></td>
                    <td width="14%" style="text-align: left; font-size: 14px;">Size </td>
                    <td style="padding-left: 8px; padding-right: 8px; font-size: 14px;">: </td>
                    <td style="text-align: left; font-size: 14px;">{{ $cutting_ticket->size }} </td>
                </tr>
                <tr>
                    <td></td>
                    <td width="14%" style="text-align: left; font-size: 14px;">Layer </td>
                    <td style="padding-left: 8px; padding-right: 8px; font-size: 14px;">: </td>
                    <td style="text-align: left; font-size: 14px;">{{ $cutting_ticket->layer }} </td>
                </tr>
            </tbody>
        </table>
        </br>
        </br>
        </br>
    @endforeach
</body>

</html>
