<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cutting Order Record</title>

    <style type="text/css">
        /* @page {
            margin-top: 1.6cm;
            margin-bottom: 1.6cm;
            margin-left: 2.5cm;
            margin-right: 2.5cm;
        } */
       
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
        }
	</style>
</head>
    @foreach ($data['serial_number'] as $cor)
        <!-- @if ($loop->iteration % 10 == 1)
            <style type="text/css">
                .page {
                    page-break-after: always;
                    border: 1px solid black;
                }
                .page:last-child {
                    page-break-after: unset;
                }
            </style>
            <div class="page"></div>
        @endif -->
        <table style=" display: inline-table; border: 1px solid black; margin-top: 4px; width: 2.5in !important; height: 1.25in !important; vertical-align: top;">
            <tbody>
                <tr style="">
                    <td width="20;" style=" height: 3cm"></td>
                    <td style=" text-align: center;" width="60">
                        <img src="data:image/png;base64, {!! base64_encode(QrCode::size(60)->generate($cor['serial_number'])) !!} ">
                    </td>
                    <td style="font-size: 14px; ">
                        {{ $cor['serial_number'] }}
                    </td>
                    <td width="20" style=""></td>
                </tr>
            </tbody>
        </table>
    @endforeach
</html>