<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print PDF</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <style type="text/css">
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
            @foreach ($data as $cutting_ticket)
                <tr>
                    <td rowspan="7" style="padding: 0px; margin: 0px;" width="100">
                    
                    <img src="https://chart.googleapis.com/chart?chs=125x125&cht=qr&chl={{ $cutting_ticket->serial_number }}&choe=UTF-8" title="Link to Google.com" />
                        <div class="serial-number">{{ $cutting_ticket->serial_number }}</div>
                    </td>
                </tr>
                <tr>
                    <td width="14%" style="text-align: left;">Ticket Number </td>
                    <td style="padding-left: 8px; padding-right: 8px;">: </td>
                    <td style="text-align: left;">{{ $cutting_ticket->ticket_number }} </td>
                </tr>
                <tr>
                    <td width="14%" style="text-align: left;">Buyer </td>
                    <td style="padding-left: 8px; padding-right: 8px;">: </td>
                    <td style="text-align: left;">{{ $cutting_ticket->buyer }} </td>
                </tr>
                <tr>
                    <td width="14%" style="text-align: left;">Size </td>
                    <td style="padding-left: 8px; padding-right: 8px;">: </td>
                    <td style="text-align: left;">{{ $cutting_ticket->size }} </td>
                </tr>
                <tr>
                    <td width="14%" style="text-align: left;">Color </td>
                    <td style="padding-left: 8px; padding-right: 8px;">: </td>
                    <td style="text-align: left;">{{ $cutting_ticket->color }} </td>
                </tr>
                <tr>
                    <td width="14%" style="text-align: left;">Layer </td>
                    <td style="padding-left: 8px; padding-right: 8px;">: </td>
                    <td style="text-align: left;">{{ $cutting_ticket->layer }} </td>
                </tr>
                <tr>
                    <td style="text-align: left;"></td>
                    <td></td>
                    <td style="text-align: left;"></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>