<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $filename }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style type="text/css">
        @page {
            margin-top: .5cm;
            margin-left: 1cm;
            margin-right: 2cm;
            margin-bottom: .5cm;
        }

        .table td, .table th {
            padding: 0.25rem;
        }

        .table thead th {
            border: 1px black solid;
            text-align: center;
            vertical-align: middle;
            font-size: 10px;
        }
        
        .table tbody td {
            text-align: center;
            border: 1px black solid;
            vertical-align: middle;
            font-weight: bold;
            font-size: 10px;
            padding-top: .25rem;
            padding-bottom: .25rem;
        }

        .report-title {
            font-weight: bold;
            font-size: 18px;
        }
        .report-subtitle {
            font-size: 14px;
        }

        .size-column {
            width: 20px;
        }

        .header-subtitle {
            font-weight: bold;
            width: 100%;
            margin-bottom: .5rem;
        }

        .header-subtitle td {
            vertical-align: bottom;
            border-bottom: 1px solid;
            font-size:11px;
        }
        .header-subtitle td.no-border {
            border: none;
        }

        .header-subtitle.signature-section {
            position: absolute;
            right:0cm;
            bottom:1cm;
        }
        
	</style>
</head>
<body>
    <div>
        <div class="row">
            <div class="col-12">
                <table width="100%" style="margin-bottom:10px;">
                    <tr>
                        <td width="50%" style="font-weight: bold; font-size: 14px;">
                            PT. GHIM LI INDONESIA
                        </td>
                        <td width="50%" style="text-align: right; font-size: 10px;">
                            RP-GLA-CUT-001<br>
                            Rev 0<br>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center; font-weight: bold; font-size: 14px;">
                            <div class="report-title">
                                Cut Piece Transfer Note
                                <br>
                                <div class="report-subtitle">{{ $transfer_note_header->serial_number }}</div>
                            </div>
                        </td>
                    </tr>
                </table>
                <table class="header-subtitle">
                    <thead>
                        <tr>
                            <td colspan="1" width="10" class="no-border text-left">M/S</td>
                            <td colspan="1" width="80" class="text-left">: {{ $transfer_note_header->location }} </td>
                            <td colspan="2" width="80" class="no-border text-right"> Style No</td>
                            <td colspan="2" width="80">: {{ $transfer_note_header->style_no }} </td>
                            <td colspan="2" width="80" class="no-border text-right"> GL No </td>
                            <td colspan="2" width="80" class="" >: {{ $transfer_note_header->gl_number}} </td>
                            <td colspan="2" width="80" class="no-border text-right"> Date </td>
                            <td colspan="2" width="110" class="" >: {{ $transfer_note_header->date}} </td>
                        </tr>
                    </thead>
                </table>

                <table class="table" id="cut_piece_stock_table">
                    <thead class="">
                        <tr>
                            <th colspan="1" rowspan="2" width="50px">No.</th>
                            <th colspan="1" rowspan="2">Color</th>
                            <th colspan="1" rowspan="2" width="70px">Table Number</th>
                            <th colspan="{{ count($size_list) }}" rowspan="1">Size</th>
                            <th colspan="1" rowspan="2" width="50px">Total</th>
                        </tr>
                        <tr>
                            @foreach($size_list as $size)
                            <th colspan="1" rowspan="1" width="30px">{{ $size['size'] }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transfer_note_detail as $key => $detail)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td style="text-align:left">{{ $detail->color }}</td>
                            <td>{{ $detail->table_number }}</td>
                            @foreach($detail->qty_per_size as $per_size)
                                <td>{{ $per_size->qty }}</td>
                            @endforeach
                            <td>{{ $detail->total_qty }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <table class="signature-section header-subtitle">
                    <thead>
                        <tr>
                            <td width="60" class="no-border">Prepared by</td>
                            <td width="100" class="">: </td>
                            <td width="30" class="no-border"></td>
                            <td width="60" class="no-border">Authorized by</td>
                            <td width="100" class="">: </td>
                            <td width="30" class="no-border"></td>
                            <td width="60" class="no-border">Received by</td>
                            <td width="100" class="">: </td>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</body>
</html>