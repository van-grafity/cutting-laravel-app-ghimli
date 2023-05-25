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
            margin-top: 0.3cm;
            margin-bottom: 0cm;
        }

		table tr td,
		table tr th{
			font-size: 8pt;
		}

        .table-nota td, .table-nota th {
            padding: 0rem 0.25rem;
			font-size: 8pt;
        }

        .company-name {
            text-align:center;
            font-weight: 700; 
            font-size: 11px;
        }
        
        .form-title {
            font-weight: normal;
            font-size: 11px;
        }

        .title-nota {
            clear:left;
            /* clear:right; */
            text-align: center;
            font-weight: bold;
            font-size: 14px;
        }
        
        .serial-number {
            float: left;
            text-align: left;
            font-size: 12px;
        }

        .serial-number-qr {
            float:right;
            text-align: right;
            /* font-size: 12px; */
        }

        .header-subtitle {
            font-weight: bold;
            width: 100%;
            margin-bottom: .3rem;
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
            height:20px;
            font-size:6pt;
        }

        .sparated-line {
            border: none;
            color: #333;
            background-color: #333;
            margin-top: 10px;
        }
        
	</style>
</head>
<body>
    <div class="">
        <div>
            <div class="serial-number">{{ $data->serial_number }}</div>

            <div class="serial-number-qr">
                <div class="qr-wrapper" style="margin-top: -15px; margin-right: -15px;">
                    <img src="https://chart.googleapis.com/chart?chs=70x70&cht=qr&chl={{ $data->serial_number }}" alt="">
                </div>
            </div>
            <div class="title-nota">
                <div class="company-name">PT. GHIM LI INDONESIA</div>
                <div class="form-title">Fabric Requisition</div>
            </div>

        </div>
        <table class="header-subtitle">
            <thead>
                <tr>
                    <td width="10" class="no-border">Dept</td>
                    <td width="100" class="">: </td>
                    <td width="300" class="no-border"></td>
                    <td width="80" class="subtitle-right"> 
                        No : {{ $data->no_laying_sheet}} <br> 
                        Date : {{ $data->date }} 
                    </td>
                </tr>
            </thead>
        </table>

        <table class="table table-nota">
            <thead class="">
                <tr>
                    <th width="250">GL  NO: {{ $data->gl_number }}</th>
                    <th>Style No: {{ $data->style }}</th>
                    <th>P/O No: {{ $data->fabric_po }}</th>
                    <th>Lay No: {{ $data->no_laying_sheet }}</th>
                </tr>
                <tr>
                    <th colspan="4">Fabric Detail / Uraian Kain : {{ $data->fabric_type }}</th>
                </tr>
                <tr>
                    <th>Color/ Warna</th>
                    <th> {{ $data->color }} </th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <th>Quantity Required / Jumlah Permintaan</th>
                    <th> {{ $data->quantity_required }} </th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <th>Quantity Issued / Jumlah dikeluarkan</th>
                    <th> {{ $data->quantity_issued }} </th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <th>Difference / Perbedaan</th>
                    <th> {{ $data->difference }} </th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
        </table>

        <table class="header-subtitle">
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

        <!-- line horizontal -->
        <hr class="sparated-line" style="margin: 0px 0px 0px 0px;">

        <table class="header-subtitle">
            <thead>
                <tr>
                    <td width="100" class="subtitle-left no-border"> 
                        Stored Used <br> 
                        Color / Warna
                    </td>
                    <td width="150" class="">: </td>
                    <td width="500" class="no-border"></td>
                </tr>
            </thead>
        </table>

        <table class="table table-nota">
            <thead class="">
                <tr>
                    <th>ROLL No / Nomor Roll</th>
                    <th>WEIGHT / Berat</th>
                    <th>ROLL No / Nomor Roll</th>
                    <th>WEIGHT / Berat</th>
                </tr>
                <tr>
                    <th>&nbsp;</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <th>&nbsp;</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <th>&nbsp;</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <th>&nbsp;</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <th>&nbsp;</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <th>&nbsp;</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <th>&nbsp;</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <th>&nbsp;</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
        </table>

        <table class="header-subtitle">
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
</body>
</html>