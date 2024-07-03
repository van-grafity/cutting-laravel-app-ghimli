<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CUTTING OUTPUT REPORT</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous"> -->
    
    <style type="text/css">
        @page {
          margin-top: 1cm;
          margin-left: 0.4cm;
          margin-right: 0.4cm;
          margin-bottom: 1cm;
        }

        .table-nota td, .table-nota th {
          padding: 0.25rem 0.25rem;
          font-size: 7pt;
          /* text-align:center; */
          vertical-align:middle;
        }

        .header-main { 
            padding-bottom: 10px;
            margin-bottom: 10px;
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
        
        .serial-number-qr {
            float:right;
            text-align: right;
            font-size: 12px;
        }
        .table-nota {
            border: 2px solid;
        }

        .table-nota thead th {
            border: 1px solid;
            vertical-align: middle;
            text-align: center;
            font-size: 7pt;
            margin: 0px !important;
            padding: 0px !important;
        }
        .table-nota tbody td {
            border: 1px solid;
            vertical-align: middle;
            text-align: center;
            font-weight: bold;
            font-size:6pt;
            padding-top: 1.5 !important;
            padding-bottom: 1.5 !important;
            padding-left: 0.3 !important;
            padding-right: 0.3 !important;
            white-space: nowrap;
        }

        .subtitle-group {
            font-size: 10px;
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
            </div>
            <div class="title-nota">
                CUTTING OUTPUT REPORT
                <br>
                <div class="subtitle-nota">
                    {{ $start_date }} - {{ $end_date }}
                </div>
                <div class="subtitle-group">
                    Group : {{ $group_name_list }}
                </div>
            </div>

        </div>

        <div class="body-nota">
            <table class="table table-nota">
                <thead class="">
                    <tr>
                        <th rowspan="2" style="width:50px;">No</th>
                        <th rowspan="2" style="width:100px;">GL#</th>
                        <th rowspan="2">Style</th>
                        <th rowspan="1" colspan="2" style="width:100px;">Cut Qty</th>
                    </tr>
                    <tr>
                        <th rowspan="1">PCS</th>
                        <th rowspan="1">Dz</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($gl_list as $key => $gl)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $gl->gl_number }}</td>
                            <td style="text-align:left; padding-left: 5 !important">{{ $gl->style }}</td>
                            <td>{{ $gl->quantity_cut_pcs }}</td>
                            <td>{{ $gl->quantity_cut_dozen }}</td>
                        </tr>
                    @endforeach
                    <tr style="background-color: #bfbfbf;">
                        <td colspan="3" style="text-align: center; font-weight: bold;">Total</td>
                        <td>{{ $general_total_pcs }}</td>
                        <td>{{ $general_total_dozen }}</td>
                    </tr>
                </tbody>
            </table>

            </br>
            </br>

        </div>
    </div>
</body>
</html>