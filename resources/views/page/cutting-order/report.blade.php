<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cutting Order Record Report</title>

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
        <div class="header-main">
            <div class="company-name">
                PT. GHIMLI INDONESIA
            </div>
            <div class="serial-number-qr">
                <div class="qr-wrapper" style="margin-top: -15px; margin-right: -15px;">
                    <img src="data:image/png;base64, {!! base64_encode(QrCode::size(70)->generate($data->serial_number)) !!} ">
                </div>
            </div>
            <div class="title-nota">
                CUTTING ORDER RECORD REPORT
                <br>
                <div class="subtitle-nota">{{ $data->serial_number }}</div>
            </div>

        </div>
        <table class="header-subtitle">
            <thead>
                <tr>
                    <td colspan="1" width="100" class="no-border text-left">Portion</td>
                    <td colspan="11" width="100" class="text-left">: {{ $data->fabric_cons }} </td>
                    <td> Date : {{ $data->created_at }} </td>
                </tr>
                <tr>
                    <td colspan="2" width="35" class="no-border"> Style No</td>
                    <td colspan="2" width="80">: {{ $data->style }} </td>
                    <td colspan="2" width="60" class="no-border text-right"> {{ $data->marker_code }} </td>
                    <td colspan="2" width="80" class="no-border text-right"> GL No </td>
                    <td colspan="2" width="110" class="" >: {{ $data->gl_number}} </td>
                    <td colspan="2" width="25" class="no-border"></td>
                    <td colspan="1" width="80" class="subtitle-right"> 
                        No : {{ $data->no_laying_sheet}} <br> 
                    </td>
                </tr>
            </thead>
        </table>
        <div class="body-nota">
            <table class="table table-nota" style="margin-bottom: 0px !important">
                <thead class="">
                    <tr>
                        <th width="40">Fabric P/O No. </th>
                        <th width="90"> {{ $data->fabric_po }} </th>
                        <th width="60">Marker Length <br> <i style="font-weight: 500;">Panjang Marker</i></th>
                        <th width="150" colspan="2"> {{ $data->marker_length }} </th>
                        <th width="100" colspan="2">Fabric Type <br> <i style="font-weight: 500;">Jenis Kain</i></th>
                        <th width="120" colspan="3"> {{ $data->fabric_type }} </th>
                        <th width="60">Cutting Lot <br> <i style="font-weight: 500;">Lot Potongan</i></th>
                        <th width="60" style="text-align:center; font-size:14px;"> {{ $data-> table_number }} </th>
                    </tr>
                    <tr>
                        <th rowspan="2">Buyer </th>
                        <th rowspan="2"> {{ $data->buyer }} </th>
                        <th rowspan="2">Width <br> <i style="font-weight: 500;">Lebar</i></th>
                        <th rowspan="2" colspan="2"> {{ $data->size_ratio }} ( {{ $data->total_size_ratio }} )</th>
                        <th colspan="2">Colour / <i style="font-weight: 500;">Warna</i></th>
                        <th colspan="3"> {{ $data->color }} </th>
                        <th rowspan="2">Laid By <br> <i style="font-weight: 500;">Dibentang Oleh</i></th>
                        <th rowspan="2"> {{ $data->laid_by }} </th>
                    </tr>
                    <tr>
                        <th colspan="2">Layer / <i style="font-weight: 500;">Lapisan</i></th>
                        <th colspan="3">{{ $data->layer }} </th>
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
                        <td style="font-size:8pt"> 1  | {{ $cor_details[0]->place_no }} </td>
                        <td style="font-size:6pt"> {{ $cor_details[0]->color }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[0]->yardage }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[0]->weight }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[0]->layer }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[0]->joint }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[0]->balance_end }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[0]->remarks }} </td>
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
                        <td style="font-size:8pt"> 2 | {{ $cor_details[1]->place_no }}</td>
                        <td style="font-size:6pt"> {{ $cor_details[1]->color }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[1]->yardage }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[1]->weight }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[1]->layer }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[1]->joint }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[1]->balance_end }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[1]->remarks }} </td>
                    </tr>
                    <tr>
                        <td style="font-size:8pt"> 3 | {{ $cor_details[2]->place_no }}</td>
                        <td style="font-size:6pt"> {{ $cor_details[2]->color }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[2]->yardage }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[2]->weight }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[2]->layer }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[2]->joint }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[2]->balance_end }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[2]->remarks }} </td>
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
                        <td style="font-size:8pt"> 4 | {{ $cor_details[3]->place_no }}</td>
                        <td style="font-size:6pt"> {{ $cor_details[3]->color }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[3]->yardage }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[3]->weight }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[3]->layer }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[3]->joint }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[3]->balance_end }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[3]->remarks }} </td>
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
                        <td style="font-size:8pt"> 5 | {{ $cor_details[4]->place_no }}</td>
                        <td style="font-size:6pt"> {{ $cor_details[4]->color }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[4]->yardage }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[4]->weight }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[4]->layer }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[4]->joint }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[4]->balance_end }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[4]->remarks }} </td>
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
                        <td style="font-size:8pt"> 6 | {{ $cor_details[5]->place_no }}</td>
                        <td style="font-size:6pt"> {{ $cor_details[5]->color }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[5]->yardage }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[5]->weight }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[5]->layer }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[5]->joint }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[5]->balance_end }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[5]->remarks }} </td>
                    </tr>
                    <tr>
                        <td style="font-size:8pt"> 7 | {{ $cor_details[6]->place_no }}</td>
                        <td style="font-size:6pt"> {{ $cor_details[6]->color }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[6]->yardage }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[6]->weight }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[6]->layer }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[6]->joint }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[6]->balance_end }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[6]->remarks }} </td>
                        <td rowspan="6" colspan="4" style="border:none">
                        <?php
                            $string_to_double = 0;
                            foreach($cor_details as $cor_detail){
                                $string_to_double += (double)$cor_detail->balance_end;
                            }
                        ?>
                        Balance End Total :
                        <p style="font-size: 11px;"> {{ $string_to_double }} Yards</p>
                        <br>
                        <br>
                        Remarks : <p style="font-size: 11px;">{{ $data->remark }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:8pt"> 8 | {{ $cor_details[7]->place_no }}</td>
                        <td style="font-size:6pt"> {{ $cor_details[7]->color }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[7]->yardage }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[7]->weight }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[7]->layer }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[7]->joint }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[7]->balance_end }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[7]->remarks }} </td>
                    </tr>
                    <tr>
                        <td style="font-size:8pt"> 9 | {{ $cor_details[8]->place_no }}</td>
                        <td style="font-size:6pt"> {{ $cor_details[8]->color }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[8]->yardage }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[8]->weight }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[8]->layer }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[8]->joint }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[8]->balance_end }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[8]->remarks }} </td>
                    </tr>
                    <tr>
                        <td style="font-size:8pt"> 10 | {{ $cor_details[9]->place_no }}</td>
                        <td style="font-size:6pt"> {{ $cor_details[9]->color }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[9]->yardage }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[9]->weight }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[9]->layer }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[9]->joint }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[9]->balance_end }} </td>
                        <td style="font-size:8pt"> {{ $cor_details[9]->remarks }} </td>
                    </tr>
                    <tr>
                        <td style="font-size:8pt"> 11 |</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="font-size:8pt"> 12 |</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="font-size:8pt"> 13 |</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td rowspan="2" colspan="4" style="border:none; font-size:12px;">Group : {{ $data->group }}</td>
                    </tr>
                    <tr>
                        <td style="font-size:8pt"> 14 |</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="font-size:8pt"> 15 |</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Colour <br> <i style="font-weight: 500;">Warna</i></td>
                        <td> {{ $data->color }} </td>
                        <td>Spread Time <br> <i style="font-weight: 500;">Waktu Bentang</i></td>
                        <td>Manpower : {{$data->manpower}} <br> <i style="font-weight: 500;">Tenaga Kerja</i></td>
                    </tr>
                    <tr>
                        <td style="font-size:8pt"> 16 |</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Layer <br> <i style="font-weight: 500;">Lapisan</i></td>
                        <td style="text-align: center; vertical-align: middle;"> {{ $data->total_layer }} </td>
                        <td style="text-align: center; vertical-align: middle;"> {{$data->spread_time}} </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="font-size:8pt"> 17 |</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Cut Qty <br> <i style="font-weight: 500;">Jumlah dipotong</i></td>
                        <td style="text-align: center; vertical-align: middle;"> {{$data->total_size_ratio_layer }} </td>
                        <td>Cutting Time <br> <i style="font-weight: 500;">Waktu Pemotongan</i></td>
                        <td>Manpower : {{$data->manpower}} <br> <i style="font-weight: 500;">Tenaga Kerja</i></td>
                    </tr>
                    <tr>
                        <td style="font-size:8pt"> 18 |</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Qty Used <br> <i style="font-weight: 500;">Jumlah dipakai</i></td>
                        <td style="text-align: center; vertical-align: middle;"> {{ $data->total_yardage }} </td>
                        <td style="text-align: center; vertical-align: middle;"> {{$data->cutting_time}} </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="font-size:8pt"> 19 |</td>
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
                        <td>Manpower : {{$data->manpower}} <br> <i style="font-weight: 500;">Tenaga Kerja</i></td>
                    </tr>
                    <tr>
                        <td style="font-size:8pt"> 20 |</td>
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
                Date Printed : {{ $data->date }} 
            </div>
        </div>
    </div>
</body>
</html>