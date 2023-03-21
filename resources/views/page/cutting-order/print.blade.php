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
            padding: 0.35rem;
        }

        .header-main { 
            padding-bottom: 20px;
        }

        .company-name {
            float: left;
            text-align: left;
            font-size: 12px;
        }

        .title-nota {
            clear:left;
            clear:right;
            text-align: center;
            font-weight: bold;
            font-size: 14px;
        }

        .serial-number {
            float:right;
            text-align: right;
            font-size: 12px;
        }

        .header-subtitle {
            width: 100%;
            margin-bottom: 1rem;
        }

        .header-subtitle td {
            vertical-align: bottom;
            border-bottom: 1px solid;
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
        }
        
	</style>
</head>
<body>
    <div class="">
        <div class="header-main">
            <div class="company-name">
                PT. GHIMLI INDONESIA
            </div>
            <div class="serial-number">
                {{ $data->serial_number }}
            </div>
            <div class="title-nota">
                CUTTING ORDER RECORD
            </div>

        </div>
        <table class="header-subtitle">
            <tbody>
                <tr>
                    <td width="120"> Style No : {{ $data->style }} </td>
                    <td width="100" class="no-border"></td>
                    <td width="110" class="text-center" > GL No : {{ $data->gl_number}} </td>
                    <td width="100" class="no-border"></td>
                    <td width="50" class="text-center"> Body </td>
                    <td width="180" class="no-border"></td>
                    <td width="80" class="subtitle-right"> 
                        No : {{ $data->no_laying_sheet}} <br> 
                        Date : {{ $data->date }} 
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="body-nota">
            <table class="table table-nota">
                <thead class="">
                    <tr>
                        <th width="50">Fabric P/O No. </th>
                        <th width="80"> {{ $data->fabric_po }} </th>
                        <th width="70">Marker Length <br> <i style="font-weight: 500;">Panjang Marker</i></th>
                        <th width="150" colspan="2"> {{ $data->marker_length }} </th>
                        <th  width="100" colspan="2">Fabric Type <br> <i style="font-weight: 500;">Jenis Kain</i></th>
                        <th width="120"> {{ $data->fabric_type }} </th>
                        <th width="50">Cutting Lot <br> <i style="font-weight: 500;">Lot Potongan</i></th>
                        <th width="80"> {{ $data-> table_number }} </th>
                    </tr>
                    <tr>
                        <th rowspan="2">Buyer </th>
                        <th rowspan="2"> {{ $data->buyer }} </th>
                        <th rowspan="2">Width <br> <i style="font-weight: 500;">Lebar</i></th>
                        <th rowspan="2" colspan="2"> {{ $data->size_ratio }} </th>
                        <th colspan="2">Colour / <i style="font-weight: 500;">Warna</i></th>
                        <th> {{ $data->color }} </th>
                        <th rowspan="2">Laid By <br> <i style="font-weight: 500;">Dibentang Oleh</i></th>
                        <th rowspan="2"> - </th>
                    </tr>
                    <tr>
                        <th colspan="2">Layer / <i style="font-weight: 500;">Lapisan</i></th>
                        <th>{{ $data->layer }}</th>
                    </tr>
                    <tr>
                        <th> Place No. </th>
                        <th> Colour / <i style="font-weight: 500;">Warna</i></th>
                        <th> Yardage <br> <i style="font-weight: 500;">Yard</i></th>
                        <th> Weight <br> <i style="font-weight: 500;">Berat</i></th>
                        <th> Layer <br> <i style="font-weight: 500;">Lapisan</i></th>
                        <th> Joint <br> <i style="font-weight: 500;">Gabung</i></th>
                        <th> Balance End <br> <i style="font-weight: 500;">Sisa</i></th>
                        <th> Remarks <br> <i style="font-weight: 500;">Keterangan</i></th>
                        <th colspan="2"></th>
                    </tr>
                </thead>
                <tbody>
                    @for($i = 0; $i < 15; $i++)
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
                </tbody>
            </table>    
        </div>
    </div>
</body>
</html>