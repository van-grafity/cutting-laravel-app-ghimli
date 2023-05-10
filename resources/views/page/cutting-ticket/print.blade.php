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
            margin-top: 1cm;
            margin-left: 1cm;
            margin-bottom: 0cm;
        }
        .detail-ticket {
            width: 100%;
            font-size: 6pt;
            font-weight: 700;
            text-align: center;
            vertical-align: middle;
            
        }

        .serial-number {
            font-size: 8pt;
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
    <div style="page-break-after:always;">
        <table class="detail-ticket" style="table-layout:fixed;">
            <table width="100%">
                <tbody>
                <!-- {{--
                    @foreach ($data as $cutting_ticket)
                        <tr>
                            <td rowspan="7" style="padding: 0px; margin: 0px;" width="100">
                           
                            <img src="https://chart.googleapis.com/chart?chs=120x120&cht=qr&chl={{ $cutting_ticket->serial_number }}&choe=UTF-8" title="Link to Google.com" />
                                <div class="serial-number">{{ $cutting_ticket->serial_number }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td width="14%">Ticket Number </td>
                            <td width="3%">: </td>
                            <td>{{ $cutting_ticket->ticket_number }} </td>
                        </tr>
                        <tr>
                            <td width="14%">Buyer </td>
                            <td>: </td>
                            <td>{{ $cutting_ticket->buyer }} </td>
                        </tr>
                        <tr>
                            <td width="14%">Size </td>
                            <td>: </td>
                            <td>{{ $cutting_ticket->size }} </td>
                        </tr>
                        <tr>
                            <td width="14%">Color </td>
                            <td>: </td>
                            <td>{{ $cutting_ticket->color }} </td>
                        </tr>
                        <tr>
                            <td width="14%">Layer </td>
                            <td>: </td>
                            <td>{{ $cutting_ticket->layer }} </td>
                        </tr>
                        <tr>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                        </tr>
                    @endforeach
                    --}} -->
                    @foreach ($data as $cutting_ticket)
                        <tr>
                            <td rowspan="7" style="padding: 0px; margin: 0px;" width="100">
                           
                            <img src="https://chart.googleapis.com/chart?chs=120x120&cht=qr&chl={{ $cutting_ticket->serial_number }}&choe=UTF-8" title="Link to Google.com" />
                                <div class="serial-number">{{ $cutting_ticket->serial_number }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td width="14%">Ticket Number </td>
                            <td width="3%">: </td>
                            <td>{{ $cutting_ticket->ticket_number }} </td>
                        </tr>
                        <tr>
                            <td width="14%">Buyer </td>
                            <td>: </td>
                            <td>{{ $cutting_ticket->buyer }} </td>
                        </tr>
                        <tr>
                            <td width="14%">Size </td>
                            <td>: </td>
                            <td>{{ $cutting_ticket->size }} </td>
                        </tr>
                        <tr>
                            <td width="14%">Color </td>
                            <td>: </td>
                            <td>{{ $cutting_ticket->color }} </td>
                        </tr>
                        <tr>
                            <td width="14%">Layer </td>
                            <td>: </td>
                            <td>{{ $cutting_ticket->layer }} </td>
                        </tr>
                        <tr>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </table>
    </div>
</body>
</html>