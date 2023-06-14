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
            size: 10.2cm 6.4cm;
            margin-left: 0.1cm;
            margin-right: 0.1cm;
            margin-top: 1cm;
            margin-bottom: 1cm;
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
	</style>
</head>

<body width="100%" height="100%">
    @foreach ($data as $cutting_ticket)
        <table>
        <tbody>
            <tr>
                <td rowspan="7" style="padding: 0px; margin: 0px; font-size: 8px;" width="100">
                
                <img src="https://chart.googleapis.com/chart?chs=125x125&cht=qr&chl={{ $cutting_ticket->serial_number }}&choe=UTF-8" title="Link to Google.com" />
                    <div class="serial-number">{{ $cutting_ticket->serial_number }}</div>
                </td>
            </tr>
            <tr>
                <td width="14%" style="text-align: left; font-size: 10px;">Ticket Number </td>
                <td style="padding-left: 8px; padding-right: 8px; font-size: 10px;">: </td>
                <td style="text-align: left; font-size: 10px;">{{ $cutting_ticket->ticket_number }} </td>
            </tr>
            <tr>
                <td width="14%" style="text-align: left; font-size: 10px;">Buyer </td>
                <td style="padding-left: 8px; padding-right: 8px; font-size: 10px;">: </td>
                <td style="text-align: left; font-size: 10px;">{{ $cutting_ticket->buyer }} </td>
            </tr>
            <tr>
                <td width="14%" style="text-align: left; font-size: 10px;">Size </td>
                <td style="padding-left: 8px; padding-right: 8px; font-size: 10px;">: </td>
                <td style="text-align: left; font-size: 10px;">{{ $cutting_ticket->size }} </td>
            </tr>
            <tr>
                <td width="14%" style="text-align: left; font-size: 10px;">Color </td>
                <td style="padding-left: 8px; padding-right: 8px; font-size: 10px;">: </td>
                <td style="text-align: left; font-size: 10px;">{{ $cutting_ticket->color }} </td>
            </tr>
            <tr>
                <td width="14%" style="text-align: left; font-size: 10px;">Layer </td>
                <td style="padding-left: 8px; padding-right: 8px; font-size: 10px;">: </td>
                <td style="text-align: left; font-size: 10px;">{{ $cutting_ticket->layer }} </td>
            </tr>
            <tr>
                <td style="text-align: left;"></td>
                <td></td>
                <td style="text-align: left;"></td>
            </tr>
        </tbody>
        </table>
    @endforeach
</body>

</html>