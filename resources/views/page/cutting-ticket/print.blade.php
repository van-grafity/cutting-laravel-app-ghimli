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
	</style>
</head>

<body>
    <table class="detail-ticket">
        <table width="100%">
            <tbody>
                <tr>
                    <td rowspan="7" style="padding: 0px; margin: 0px;" width="100">
                        <img src="https://chart.googleapis.com/chart?chs=120x120&cht=qr&chl={{ $data->serial_number }}" alt="">
                        <div class="serial-number">{{ $data->serial_number }}</div>
                    </td>
                </tr>
                <tr>
                    <td width="14%">Ticket Number </td>
                    <td width="3%">: </td>
                    <td>{{ $data->ticket_number }} </td>
                </tr>
                <tr>
                    <td width="14%">Buyer </td>
                    <td>: </td>
                    <td>{{ $data->buyer }} </td>
                </tr>
                <tr>
                    <td width="14%">Size </td>
                    <td>: </td>
                    <td>{{ $data->size }} </td>
                </tr>
                <tr>
                    <td width="14%">Color </td>
                    <td>: </td>
                    <td>{{ $data->color }} </td>
                </tr>
                <tr>
                    <td width="14%">Layer </td>
                    <td>: </td>
                    <td>{{ $data->layer }} </td>
                </tr>
                <tr>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                </tr>
            </tbody>
        </table>
    </table>
</body>
</html>