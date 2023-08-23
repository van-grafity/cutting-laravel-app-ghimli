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
            size: 21.8cm 13.8cm;
            margin-top: 0.35cm;
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
        @foreach ($data as $fbr)
            <table width="100%" style="padding-top: 0 !important;
        padding-bottom: 0 !important;
        padding-left: 0 !important;
        padding-right: 0 !important;">
                <tr>
                    <td width="20%;"></td>
                    <td width="60%;" style="font-weight: bold; font-size: 14px; text-align: center; margin: 0 !important; padding: 0 !important;">
                        <span style="margin: 0 !important; padding: 0 !important;">PT. GHIM LI INDONESIA</span>
                    </td>
                    <td width="20%;" style="text-align: right; font-size: 10px;">
                        FM-GLA-CUT-002
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align: center; font-weight: normal; font-size: 10px;">
                        FABRIC REQUISITION
                    </td>
                    <td style="text-align: right; font-size: 10px;">
                        Rev. 00
                    </td>
                </tr>
            </table>
            <table class="header-subtitle">
                <thead>
                    <tr>
                        <td width="10" class="no-border">Dept</td>
                        <td width="100" class="">: </td>
                        <td width="300" class="no-border" style="text-align: center;"> {{ $fbr->serial_number }} </td>
                        <td width="80" class="subtitle-right"> 
                            No : {{ $fbr->no_laying_sheet}} <br> 
                            Date : {{ $fbr->date }} 
                        </td>
                    </tr>
                </thead>
            </table>

            <table class="table table-nota" style="margin: 0px 0px 0px 0px !important;">
                <thead class="">
                    <tr>
                        <th width="150">GL  NO: {{ $fbr->gl_number }}</th>
                        <th>Style No: {{ $fbr->style }}</th>
                        <th width="15%">P/O No: {{ $fbr->fabric_po }}</th>
                        <th width="17%">Lay No: {{ $fbr->no_laying_sheet }}</th>
                    </tr>
                    <tr>
                        <th>Fabric Detail / Uraian Kain</th>
                        <th> {{ $fbr->fabric_type }} </th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th>Color/ Warna</th>
                        <th colspan="3"> {{ $fbr->color }} </th>
                    </tr>
                    <tr>
                        <th>Quantity Required / Jumlah Permintaan</th>
                        <th> {{ $fbr->quantity_required }} </th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th>Quantity Issued / Jumlah dikeluarkan</th>
                        <th> {{ $fbr->quantity_issued }} </th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th>Difference / Perbedaan</th>
                        <th> {{ $fbr->difference }} </th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
            </table>

            <table width="100%" style="font-size: 11px; font-weight: bold; align-items: center; padding-top: 10px;">
                <thead>
                    <tr>
                        <td width="60" class="no-border">Prepared by</td>
                        <td class="">: </td>
                        <td>
                            <div style="display:inline-block; text-align:center;">
                                <span>MELDA (58734)</span>
                                <hr style="border: none; height: 1px; background-color: black; margin-top: 0px; margin-bottom: 0px; width: 130px;">
                            </div>
                        </td>
                        <td width="60" class="no-border">Authorized by</td>
                        <td class="">: </td>
                        <td>
                            <div style="display:inline-block; text-align:center;">
                                <span>ROBERT (36120)</span>
                                <hr style="border: none; height: 1px; background-color: black; margin-top: 0px; margin-bottom: 0px; width: 130px;">
                            </div>
                        </td>
                        <td width="60" class="no-border">Received by</td>
                        <td class="">:</td>
                        <td>
                            <div style="display:inline-block; text-align:center;">
                                <div style="height: 18px;"></div>
                                <hr style="border: none; height: 1px; background-color: black; margin-top: 0px; margin-bottom: 0px; width: 130px;">
                            </div>
                        </td>
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
                        <th style="text-align: center">Roll No / Nomor Roll</th>
                        <th style="text-align: center">Weight / Berat</th>
                        <th style="text-align: center">Roll No / Nomor Roll</th>
                        <th style="text-align: center">Weight / Berat</th>
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
        @endforeach
    </div>
</body>
</html>