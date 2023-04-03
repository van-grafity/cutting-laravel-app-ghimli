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
        .sticker-wrapper {
            width: 150px;
            padding:1px;
            /* background-color: #f0f0f0; */
            border: solid 1px black;
        }
        
        .detail-ticket {
            padding: 10px;
            padding-top: 0px;
            margin-top: -15px;
			font-size: 6pt;
            font-weight: bold;
        }

        .serial-number {
            text-align: center;
            font-size: 8pt;
            font-weight: 700;
            padding-bottom: 10px;
        }
	</style>
</head>
<body>
    <div class="">
        <div class="row">
            <div class="col-sm-12">
                <div class="sticker-wrapper">
                    <div class="qr-wrapper text-center" style="">
                        <img src="https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl={{ $data->serial_number }}" alt="">
                    </div>
                    <div class="detail-ticket text-left">
                        <table>
                            <tbody>
                                <tr>
                                    <td colspan="3" class="serial-number">{{ $data->serial_number }} </td>
                                </tr>
                                <tr>
                                    <td>Ticket Number </td>
                                    <td>: </td>
                                    <td>{{ $data->ticket_number }} </td>
                                </tr>
                                <tr>
                                    <td>Buyer </td>
                                    <td>: </td>
                                    <td>{{ $data->buyer }} </td>
                                </tr>
                                <tr>
                                    <td>Size </td>
                                    <td>: </td>
                                    <td>{{ $data->size }} </td>
                                </tr>
                                <tr>
                                    <td>Color </td>
                                    <td>: </td>
                                    <td>{{ $data->color }} </td>
                                </tr>
                                <tr>
                                    <td>Layer </td>
                                    <td>: </td>
                                    <td>{{ $data->layer }} </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>