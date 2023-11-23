<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cutting Order Record</title>
    <!-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    
    <style type="text/css">
        @page {
            margin-top: 1.2cm;
            margin-left: 1.2cm;
            margin-bottom: 0cm;
        }
        /* header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 3cm;
            background-color: #fff;
            color: #000;
            text-align: center;
            line-height: 30px;
            Only display on the first page
            display: block;
            page-break-after: always;
        } */

		table tr td,
		table tr th{
			font-size: 8pt;
		}

        .table-nota td, .table-nota th {
            padding: 0rem 0.25rem;
			font-size: 7pt;
        }

        .header-main { 
            /* padding-bottom: 5px; */
        }

        .company-name {
            float: left;
            text-align: left;
            font-size: 12px;
        }

        .title-nota {
            clear:left;
            /* clear:right; */
            text-align: center;
            font-weight: bold;
            font-size: 14px;
        }
        
        .subtitle-nota {
            font-weight: Normal;
            font-size: 11px;
        }

        .serial-number-qr {
            float:right;
            text-align: right;
            /* font-size: 12px; */
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

        .subtitle-right {
            /* text-align: right */
        }

        .table-nota {
            border: 2px solid;
        }

        .table-nota thead th {
            border: 1px solid;
            vertical-align: middle;
        }
        .table-nota tbody td {
            border: 1px solid;
            font-weight: bold;
            height:25px;
            font-size:6pt;
        }
        
	</style>
</head>
<body>
    <div class="">
        @foreach ($data as $cor)
        <div class="header-main">
            <div class="company-name">
                PT. GHIMLI INDONESIA
            </div>
            <div class="serial-number-qr">
                <div class="qr-wrapper" style="margin-top: -15px; margin-right: -15px;">
                    <img src="data:image/png;base64, {!! base64_encode(QrCode::size(70)->generate($cor['serial_number'])) !!} ">
                </div>
            </div>
            <div class="title-nota">
                CUTTING ORDER RECORD
                <br>
                <div class="subtitle-nota">{{ $cor['serial_number'] }}</div>
            </div>

        </div>
        <table class="header-subtitle">
            <thead>
                <tr>
                    <td colspan="1" width="100" class="no-border text-left">Portion</td>
                    <td colspan="11" width="100" class="text-left">: {{ $cor['fabric_cons'] }} </td>
                    <td> Date : {{ $cor['created_at'] }} </td>
                </tr>
                <tr>
                    <td colspan="2" width="35" class="no-border"> Style No</td>
                    <td colspan="2" width="80">: {{ $cor['style'] }} </td>
                    <td colspan="2" width="60" class="no-border text-right"> {{ $cor['marker_code'] }} </td>
                    <td colspan="2" width="80" class="no-border text-right"> GL No </td>
                    <td colspan="2" width="110" class="" >: {{ $cor['gl_number']}} </td>
                    <td colspan="2" width="25" class="no-border"></td>
                    <td colspan="1" width="80" class="subtitle-right"> 
                        No : {{ $cor['no_laying_sheet']}} <br> 
                    </td>
                </tr>
            </thead>
        </table>
        <div class="body-nota">
            <table class="table table-nota" style="margin-bottom: 0px !important">
                <thead class="">
                    <tr>
                        <th width="40">Fabric P/O No. </th>
                        <th width="90"> {{ $cor['fabric_po'] }} </th>
                        <th width="60">Marker Length <br> <i style="font-weight: 500;">Panjang Marker</i></th>
                        <th width="150" colspan="2"> {{ $cor['marker_length'] }} </th>
                        <th width="100" colspan="2">Fabric Type <br> <i style="font-weight: 500;">Jenis Kain</i></th>
                        <th width="120" colspan="3"> {{ $cor['fabric_type'] }} </th>
                        <th width="60">Cutting Lot <br> <i style="font-weight: 500;">Lot Potongan</i></th>
                        <th width="60" style="text-align:center; font-size:14px;"> {{ $cor['table_number'] }} </th>
                    </tr>
                    <tr>
                        <th rowspan="2">Buyer </th>
                        <th rowspan="2"> {{ $cor['buyer'] }} </th>
                        <th rowspan="2">Width <br> <i style="font-weight: 500;">Lebar</i></th>
                        <th rowspan="2" colspan="2"> {{ $cor['size_ratio'] }} </th>
                        <th colspan="2">Colour / <i style="font-weight: 500;">Warna</i></th>
                        <th colspan="3"> {{ $cor['color'] }} </th>
                        <th rowspan="2">Laid By <br> <i style="font-weight: 500;">Dibentang Oleh</i></th>
                        <th rowspan="2"> - </th>
                    </tr>
                    <tr>
                        <th colspan="2">Layer / <i style="font-weight: 500;">Lapisan</i></th>
                        <th colspan="3">{{ $cor['layer'] }}</th>
                    </tr>
                    <tr>
                        <th> Place No. </th>
                        <th> Colour / <i style="font-weight: 500;">Warna</i></th>
                        <th> Yardage <br> <i style="font-weight: 500;">Yard</i></th>
                        <th> Weight <br> <i style="font-weight: 500;">Berat</i></th>
                        <th> Layer <br> <i style="font-weight: 500;">Lapisan</i></th>
                        <th> Joint <br> <i style="font-weight: 500;">Gabung</i></th>
                        <th width="50"> Balance End <br> <i style="font-weight: 500;">Sisa</i></th>
                        <th> Remarks <br> <i style="font-weight: 500;">Keterangan</i></th>
                        <th colspan="4"></th>
                    </tr>
                </thead>
                <tbody>
                    @for($i = 0; $i < 0; $i++)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endfor

                    <tr>
                        <td style="font-size:10pt" > 1 </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td rowspan="2" style="vertical-align:middle;">1 Layer</td>
                        <td rowspan="2" style="vertical-align:middle;" width="50">: 
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp; 
                            &nbsp;&nbsp;&nbsp;&nbsp; 
                            &nbsp; 
                            Kg
                        </td>
                        <td rowspan="3" colspan="2"></td>
                    </tr>
                    <tr>
                        <td style="font-size:10pt" > 2 </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="font-size:10pt" > 3 </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Replacement</td>
                        <td rowspan="1" style="vertical-align:middle;">: 
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp; 
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp; 
                            Kg
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:10pt" > 4 </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Shortend</td>
                        <td rowspan="1" style="vertical-align:middle;">: 
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp; 
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp; 
                            Kg
                        </td>
                        <td rowspan="3" colspan="2" style="vertical-align:bottom; text-align:center">Supervisor:</td>
                    </tr>
                    <tr>
                        <td style="font-size:10pt" > 5 </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td rowspan="2" style="vertical-align:middle;" >Ball Roll</td>
                        <td rowspan="2" style="vertical-align:middle;">: 
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp; 
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp; 
                            Kg
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:10pt" > 6 </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="font-size:10pt" > 7 </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td rowspan="6" colspan="4" style="border:none">
                        <br>
                        Remarks : <p style="font-size: 11px;">{{ $cor['remark'] }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:10pt" > 8 </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="font-size:10pt" > 9 </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="font-size:10pt" > 10 </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="font-size:10pt" > 11 </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="font-size:10pt" > 12 </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="font-size:10pt" > 13 </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td rowspan="2" colspan="4" style="border:none; font-size:12px;">Group :</td>
                    </tr>
                    <tr>
                        <td style="font-size:10pt" > 14 </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="font-size:10pt" > 15 </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Colour <br> <i style="font-weight: 500;">Warna</i></td>
                        <td></td>
                        <td>Spread Time <br> <i style="font-weight: 500;">Waktu Bentang</i></td>
                        <td>Manpower : <br> <i style="font-weight: 500;">Tenaga Kerja</i></td>
                    </tr>
                    <tr>
                        <td style="font-size:10pt" > 16 </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Layer <br> <i style="font-weight: 500;">Lapisan</i></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="font-size:10pt" > 17 </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Cut Qty <br> <i style="font-weight: 500;">Jumlah dipotong</i></td>
                        <td></td>
                        <td>Cutting Time <br> <i style="font-weight: 500;">Waktu Pemotongan</i></td>
                        <td>Manpower : <br> <i style="font-weight: 500;">Tenaga Kerja</i></td>
                    </tr>
                    <tr>
                        <td style="font-size:10pt" > 18 </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Qty Used <br> <i style="font-weight: 500;">Jumlah dipakai</i></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="font-size:10pt" > 19 </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Sub Fabric <br> <i style="font-weight: 500;">Subtitusi Kain</i></td>
                        <td></td>
                        <td>Bundle Time <br> <i style="font-weight: 500;">Waktu Ikat</i></td>
                        <td>Manpower : <br> <i style="font-weight: 500;">Tenaga Kerja</i></td>
                    </tr>
                    <tr>
                        <td style="font-size:10pt" > 20 </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Total</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <div style="font-size: 10px; margin-top: 0">
                Date Print : {{ $cor['date'] }} | Print By : {{ $cor['printed_by'] }}
            </div>
        </div>
        @endforeach
    </div>
</body>
</html>