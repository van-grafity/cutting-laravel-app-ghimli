<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cutting Order Record</title>

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
                        <th rowspan="2" style="width: 3%;">MI QTY</th>
                        <th rowspan="1">{{ $date_filter }}</th>
                        <th colspan="{{ count($groups) }}">Cutting Output (Group)</th>
                        <th rowspan="2">Total Qty </br> per day</th>
                        <th rowspan="2">Acumulation </br> (pcs)</th>
                        <th rowspan="2">Completed (%)</th>
                        <th rowspan="2">Replacement for </br> Sewing</th>
                    </tr>
                    <tr>
                        <th rowspan="1">Previous Balance</th>
                        @foreach($groups as $key_group => $group)
                            <th rowspan="1" colspan="1"><?php
                                $group_name = $group->group_name;
                                $group_name = substr($group_name, 0, 8);
                                $group_name = str_replace('Group', '', $group_name);
                                $group_name = str_replace(' ', '', $group_name);
                                echo $group_name;
                            ?></th>

                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $key => $item)
                    @php $count_lp = count($item->laying_plannings); @endphp
                        @foreach($item->laying_plannings as $key_lp => $laying_planning)
                            <tr>
                                <!-- @if ($key_lp == 0)
                                    <td rowspan="{{$count_lp}}">{{ $item->buyer }}</td>
                                @endif -->
                                <td>
                                    {{
                                        $item->buyer
                                    }}
                                </td>
                                <td>{{ $laying_planning->style }}</td>
                                <td>{{ $laying_planning->gl_number }}</td>
                                <td style="text-align: left; padding-left: 2 !important;">{{ $laying_planning->color }}</td>
                                <td>{{ $laying_planning->order_qty }}</td>
                                <td>{{ $laying_planning->previous_balance }}</td>
                                @foreach($laying_planning->qty_per_groups as $key_group => $group)
                                    <td>{{ $group->qty_group }}</td>
                                @endforeach
                                <td>{{ $laying_planning->total_qty_per_day}}</td>
                                <td>{{ $laying_planning->accumulation}}</td>
                                <td>{{ $laying_planning->completed}}</td>
                                <td>0</td>
                            </tr>
                            @if ($key_lp == $count_lp - 1)
                                <tr style="background-color: #d9d9d9;">
                                    <td colspan="4" style="text-align: center; font-weight: bold;">Subtotal</td>
                                    <td><b><?php
                                        $total_mi_qty = 0;
                                        foreach($item->laying_plannings as $key_lp => $laying_planning) {
                                            $total_mi_qty += $laying_planning->order_qty;
                                        }
                                        echo $total_mi_qty;
                                    ?></td>
                                    <td><b><?php
                                        $total_prev = 0;
                                        foreach($item->laying_plannings as $key_lp => $laying_planning) {
                                            $total_prev += $laying_planning->previous_balance;
                                        }
                                        echo $total_prev;
                                    ?></td>
                                    @foreach($laying_planning->qty_per_groups as $key_group => $group)
                                        <td><b><?php
                                            $total_qty_group = 0;
                                            foreach($item->laying_plannings as $key_lp => $laying_planning) {
                                                $total_qty_group += $laying_planning->qty_per_groups[$key_group]->qty_group;
                                            }
                                            echo $total_qty_group;
                                        ?></td>
                                    @endforeach
                                    <td><b><?php
                                        $total_qty_per_day = 0;
                                        foreach($item->laying_plannings as $key_lp => $laying_planning) {
                                            $total_qty_per_day += $laying_planning->total_qty_per_day;
                                        }
                                        echo $total_qty_per_day;
                                    ?></td>
                                    <td><b><?php
                                        $total_accumulation = 0;
                                        foreach($item->laying_plannings as $key_lp => $laying_planning) {
                                            $total_accumulation += $laying_planning->accumulation;
                                        }
                                        echo $total_accumulation;
                                    ?></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endif
                        @endforeach
                    @endforeach
                    <tr style="background-color: #bfbfbf;">
                        <td colspan="4" style="text-align: center; font-weight: bold;">Total</td>
                        <td><b><?php
                            $total_mi_qty = 0;
                            foreach($data as $key => $item) {
                                foreach($item->laying_plannings as $key_lp => $laying_planning) {
                                    $total_mi_qty += $laying_planning->order_qty;
                                }
                            }
                            echo $total_mi_qty;
                        ?></td>
                        <td><b><?php
                            $total_prev = 0;
                            foreach($data as $key => $item) {
                                foreach($item->laying_plannings as $key_lp => $laying_planning) {
                                    $total_prev += $laying_planning->previous_balance;
                                }
                            }
                            echo $total_prev;
                        ?></td>
                        @foreach($groups as $key_group => $group)
                            <td><b><?php
                                $total_qty_group = 0;
                                foreach($data as $key => $item) {
                                    foreach($item->laying_plannings as $key_lp => $laying_planning) {
                                        $total_qty_group += $laying_planning->qty_per_groups[$key_group]->qty_group;
                                    }
                                }
                                echo $total_qty_group;
                            ?></td>
                        @endforeach
                        <td><b><?php
                            $total_qty_per_day = 0;
                            foreach($data as $key => $item) {
                                foreach($item->laying_plannings as $key_lp => $laying_planning) {
                                    $total_qty_per_day += $laying_planning->total_qty_per_day;
                                }
                            }
                            echo $total_qty_per_day;
                        ?></td>
                        <td><b><?php
                            $total_accumulation = 0;
                            foreach($data as $key => $item) {
                                foreach($item->laying_plannings as $key_lp => $laying_planning) {
                                    $total_accumulation += $laying_planning->accumulation;
                                }
                            }
                            echo $total_accumulation;
                        ?></td>
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