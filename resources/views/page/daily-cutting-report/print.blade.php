<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Cutting Report</title>
    <!-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    
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
        
	</style>
</head>
<body>
    <div class="">
        <div class="header-main">
            <div class="company-name">
                PT. GHIMLI INDONESIA
            </div>
            <div class="serial-number-qr">
                <div>RP-GLA-CUT-004</div>
                <div>Rev 0</div>
            </div>
            <div class="title-nota">
                DAILY CUTTING OUTPUT REPORT
                <br>
                <div class="subtitle-nota"></div>
            </div>

        </div>

        <div class="body-nota">
            <table class="table table-nota">
                <thead class="">
                    <tr>
                        <th rowspan="2" style="width: 5%;">Buyer</th>
                        <th rowspan="2" style="width: 7%;">Style</th>
                        <th rowspan="2" style="width: 5%;">GL#</th>
                        <th rowspan="2" style="width: 10%;">COLOR</th>
                        <th rowspan="1" colspan="2">{{ $date_filter }}</th>
                        <th colspan="{{ count($groups) }}">Cutting Output (Group)</th>
                        <th rowspan="2">Total Qty </br> per day</th>
                        <th rowspan="2">Previous Acumulation </br> (pcs)</th>
                        <th rowspan="2">Acumulation </br> (pcs)</th>
                        <th rowspan="2">Completed (%)</th>
                        <th rowspan="2">Replacement for </br> Sewing</th>
                    </tr>
                    <tr>
                        <th rowspan="1" style="width: 3%;">MI QTY</th>
                        <th rowspan="1">MI Balance <br> (pcs)</th>
                        @foreach($groups as $key_group => $group)
                            <th rowspan="1" colspan="1"> {{ $group->group_name_show }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data_per_buyer as $key => $item)
                        @php 
                            $count_lp = count($item->laying_plannings);
                        @endphp
                        @foreach($item->laying_plannings as $key_lp => $laying_planning)
                            <tr>
                                <td>{{ $item->buyer }} </td>
                                <td>{{ $laying_planning->style }}</td>
                                <td>{{ $laying_planning->gl_number }}</td>
                                <td style="text-align: left; padding-left: 2 !important;">
                                    {{ $laying_planning->color }}
                                    <!-- {{ (strlen($laying_planning->color) > 30) ? substr($laying_planning->color,0,30).'...' : $laying_planning->color }} -->
                                </td>
                                <td>{{ $laying_planning->order_qty }}</td>
                                <td>{{ $laying_planning->balance_to_cut }}</td>
                                @foreach($laying_planning->qty_per_groups as $key_group => $group)
                                    <td>{{ $group->qty_group }}</td>
                                @endforeach
                                <td>{{ $laying_planning->total_qty_per_day}}</td>
                                <td>{{ $laying_planning->previous_accumulation}}</td>
                                <td>{{ $laying_planning->accumulation}}</td>
                                <td>{{ $laying_planning->completed}}</td>
                                <td>0</td>
                            </tr>
                            @php $count_lp = count($item->laying_plannings); @endphp

                            @if ($key_lp == $count_lp - 1)
                                <tr style="background-color: #d9d9d9;">
                                    <td colspan="4" style="text-align: center; font-weight: bold;">Subtotal</td>
                                    <td>{{ $item->subtotal_mi_qty }}</td>
                                    <td>{{ $item->subtotal_balance_to_cut }}</td>
                                    @foreach($laying_planning->qty_per_groups as $key_group => $group)
                                        <td><b><?php
                                            $total_qty_group = 0;
                                            foreach($item->laying_plannings as $laying_planning) {
                                                $total_qty_group += $laying_planning->qty_per_groups[$key_group]->qty_group;
                                            }
                                            echo $total_qty_group;
                                        ?></td>
                                    @endforeach
                                    <td>{{ $item->subtotal_qty_per_day }}</td>
                                    <td>{{ $item->subtotal_previous_accumulation }}</td>
                                    <td>{{ $item->subtotal_accumulation }}</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endif
                        @endforeach
                    @endforeach
                    <tr style="background-color: #bfbfbf;">
                        <td colspan="4" style="text-align: center; font-weight: bold;">Total</td>
                        <td>{{ $general_total->general_total_mi_qty}}</td>
                        <td>{{ $general_total->general_total_balance_to_cut}}</td>
                        
                        @foreach($groups as $key_group => $group)
                            <td><b><?php
                                $total_qty_group = 0;
                                foreach($data_per_buyer as $key => $item) {
                                    foreach($item->laying_plannings as $key_lp => $laying_planning) {
                                        $total_qty_group += $laying_planning->qty_per_groups[$key_group]->qty_group;
                                    }
                                }
                                echo $total_qty_group;
                            ?></td>
                        @endforeach
                        <td>{{ $general_total->general_total_qty_per_day}}</td>
                        <td>{{ $general_total->general_total_previous_accumulation}}</td>
                        <td>{{ $general_total->general_total_accumulation}}</td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            </br>
            </br>

            <table width="100%" style="font-size: 12px; font-family: Times New Roman, Times, serif; font-weight: bold; position: absolute; bottom: 50px;">
                <tr>
                    <td width="50%" style="text-align: center;">
                        <p>Prepared by:</p>
                        <p></p>
                        <div style="display:inline-block; text-align:center;">
                            <div style="height: 18px;"></div>
                            <hr style="border: none; height: 1px; background-color: black; margin-top: 0px; margin-bottom: 0px; width: 90px;">
                        </div>
                    </td>
                    <td width="50%" style="text-align: center;">
                        <p>Authorized by:</p>
                        <p></p>
                        <div style="display:inline-block; text-align:center;">
                            <div style="height: 18px;"></div>
                            <hr style="border: none; height: 1px; background-color: black; margin-top: 0px; margin-bottom: 0px; width: 90px;">
                        </div>
                    </td>
                    <td width="50%" style="text-align: center;">
                        <p>Approved by:</p>
                        <p></p>
                        <div style="display:inline-block; text-align:center;">
                            <div style="height: 18px;"></div>
                            <hr style="border: none; height: 1px; background-color: black; margin-top: 0px; margin-bottom: 0px; width: 90px;">
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>