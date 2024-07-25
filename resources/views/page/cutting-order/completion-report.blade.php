<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CUTTING COMPLETION REPORT</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous"> -->
    <style type="text/css">
        table {
            font-family: 'Calibri', Helvetica, Arial, sans-serif;
        }

        .table-laying-planning {
            font-size: 10px;
            font-weight: bold;
            border-collapse: collapse;
            width: 100%;
        }

        .table-laying-planning td {
            padding: 2px;
        }

        .table-laying-planning th {
            padding: 2px;
            text-align: center;
        }

        .table-laying-planning tr {
            border: 1px solid black;
        }

        .table-laying-planning tr td {
            border: 1px solid black;
            text-align: center;
        }

        .table-laying-planning tr th {
            border: 1px solid black;
            padding: 2px;
        }

        .table-laying-planning tbody tr td:first-child {
            padding-left: 5px;
            text-align: left;
        }

        .table-laying-planning .text-left {
            padding-left: 5px;
            text-align: left;
        }



        .table-fabric-consumption {
            font-size: 10px;
            font-weight: bold;
            border-collapse: collapse;
            width: 100%;
            margin-top:20px;
            margin-bottom:20px;
            padding-bottom:20px;
        }

        .table-fabric-consumption td {
            padding: 2px;
        }

        .table-fabric-consumption th {
            padding: 2px;
            text-align: center;
        }

        .table-fabric-consumption tr {
            border: 1px solid black;
        }

        .table-fabric-consumption tr td {
            border: 1px solid black;
            text-align: center;
        }

        .table-fabric-consumption tr th {
            border: 1px solid black;
            padding: 2px;
        }

        .table-fabric-consumption .text-left {
            padding-left: 5px;
        }

    </style>
</head>
<body>
    <div>
        <table width="100%" style="margin-bottom:10px;">
            <tr>
                <td width="50%" style="font-weight: bold; font-size: 14px;">
                    PT. GHIM LI INDONESIA
                </td>
                <td width="50%" style="text-align: right; font-size: 10px;">
                    RP-GLA-CUT-005<br>
                    Rev 0<br>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center; font-weight: bold; font-size: 14px;">
                    CUTTING COMPLETION REPORT
                    <br>
                </td>
            </tr>
        </table>
        <table style="font-size: 11px; font-weight: bold; width: 100% !important;">
            <tr>
                <td width="10%">GL#</td>
                <td width="1.5%">:</td>
                <td>{{ $completion_data->gl_number }}</td>
                <td width="15%">FABRIC TYPE</td>
                <td width="1.5%">:</td>
                <td width="35%">{{ $completion_data->fabric_type }}</td>
                <td>DATE</td>
                <td width="1.5%">:</td>
                <td>{{ $completion_data->plan_date }}</td>
            </tr>
            <tr>
                <td>PO. NO</td>
                <td>:</td>
                <td>{{ $completion_data->fabric_po }}</td>
                <td>FABRIC CONS</td>
                <td>:</td>
                <td>{{ $completion_data->fabric_cons }}</td>
                <td>DELIVERY DATE</td>
                <td>:</td>
                <td>{{ $completion_data->delivery_date }}</td>
            </tr>
            <tr>
                <td>BUYER</td>
                <td>:</td>
                <td>{{ $completion_data->buyer }}</td>
                <td>TOTAL OUTPUT QTY</td>
                <td>:</td>
                <td>{{ $completion_data->total_output_qty }}</td>
                <td>PO Marker</td>
                <td>:</td>
                <td> - </td>
            </tr>
            <tr>
                <td>STYLE</td>
                <td>:</td>
                <td>{{ $completion_data->style }} </td>
                <td>DIFF (Output - MI)</td>
                <td>:</td>
                <td>{{ $completion_data->diff_output_mi_qty }}</td>
                <td>Actual Marker Length</td>
                <td>:</td>
                <td> - </td>
            </tr>
            <tr>
                <td>MI QTY</td>
                <td>:</td>
                <td>{{ $completion_data->total_mi_qty }}</td>
                <td>TOTAL REPLACEMENT</td>
                <td>:</td>
                <td>{{ $completion_data->total_replacement }}</td>
            </tr>
        </table>

        <table width="100%" style="margin-top:20px;">
            <tbody>
                @foreach ($laying_planning_new as $laying_planning_row)
                <tr>
                    @foreach ($laying_planning_row as $laying_planning)
                    <td>
                        <table class="table table-laying-planning" style="margin-bottom: 5px;">
                            <thead>
                                <tr>
                                    <th class="text-left"> COLOR </th>
                                    <th colspan="{{ $laying_planning->color_colspan }}">{{ $laying_planning->color->color }}</th>
                                    <th style="color:{{ $laying_planning->diff_percentage_color }};" >{{ $laying_planning->diff_percentage }} %</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="width:80px;">SIZE</td>
                                    @foreach($laying_planning->layingPlanningSize as $size)
                                    <td>{{ $size->size->size }}</td>
                                    @endforeach
                                    <td style="width:60px;">TOTAL</td>
                                </tr>
                                <tr>
                                    <td>MI QTY</td>
                                    @foreach($laying_planning->layingPlanningSize as $size)
                                    <td>{{ $size->quantity }}</td>
                                    @endforeach
                                    <td>{{ $laying_planning->order_qty}}</td>
                                </tr>
                                <tr>
                                    <td>OUTPUT QTY</td>
                                    @foreach($laying_planning->cut_qty_per_size as $qty_each_size)
                                    <td>{{ $qty_each_size }}</td>
                                    @endforeach
                                    <td>{{ $laying_planning->cut_qty_all_size}}</td>
                                </tr>
                                <tr>
                                    <td>DIFF</td>
                                    @foreach($laying_planning->diff_qty_per_size as $diff_each_size)
                                    <td style="{{ ($diff_each_size < 0) ? 'color:red;' : '' }}" >{{ $diff_each_size }}</td>
                                    @endforeach
                                    <td style="text-align: center; {{ ($laying_planning->diff_qty_all_size < 0) ? 'color:red;' : '' }}">{{ $laying_planning->diff_qty_all_size}}</td>
                                </tr>
                                <tr>
                                    <td>REPLACEMENT</td>
                                    @foreach($laying_planning->replacement_qty_per_size as $replacement_each_size)
                                    <td>{{ $replacement_each_size }}</td>
                                    @endforeach
                                    <td>{{ $laying_planning->replacement_qty_all_size}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>


        <table width="100%" style="margin-top:20px;" class="d-none">
            <tbody>
                @foreach ($laying_planning_parents as $laying_planning)
                <tr>
                    <td>
                        <table class="table table-laying-planning" style="margin-bottom: 5px;">
                            <thead>
                                <tr>
                                    <th class="text-left"> COLOR </th>
                                    <th colspan="{{ $laying_planning->color_colspan }}">{{ $laying_planning->color->color }}</th>
                                    <th style="color:{{ $laying_planning->diff_percentage_color }};" >{{ $laying_planning->diff_percentage }} %</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="width:80px;">SIZE</td>
                                    @foreach($laying_planning->layingPlanningSize as $size)
                                    <td>{{ $size->size->size }}</td>
                                    @endforeach
                                    <td style="width:60px;">TOTAL</td>
                                </tr>
                                <tr>
                                    <td>MI QTY</td>
                                    @foreach($laying_planning->layingPlanningSize as $size)
                                    <td>{{ $size->quantity }}</td>
                                    @endforeach
                                    <td>{{ $laying_planning->order_qty}}</td>
                                </tr>
                                <tr>
                                    <td>OUTPUT QTY</td>
                                    @foreach($laying_planning->cut_qty_per_size as $qty_each_size)
                                    <td>{{ $qty_each_size }}</td>
                                    @endforeach
                                    <td>{{ $laying_planning->cut_qty_all_size}}</td>
                                </tr>
                                <tr>
                                    <td>DIFF</td>
                                    @foreach($laying_planning->diff_qty_per_size as $diff_each_size)
                                    <td style="{{ ($diff_each_size < 0) ? 'color:red;' : '' }}" >{{ $diff_each_size }}</td>
                                    @endforeach
                                    <td style="text-align: center; {{ ($laying_planning->diff_qty_all_size < 0) ? 'color:red;' : '' }}">{{ $laying_planning->diff_qty_all_size}}</td>
                                </tr>
                                <tr>
                                    <td>REPLACEMENT</td>
                                    @foreach($laying_planning->replacement_qty_per_size as $replacement_each_size)
                                    <td>{{ $replacement_each_size }}</td>
                                    @endforeach
                                    <td>{{ $laying_planning->replacement_qty_all_size}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                    @foreach ($laying_planning->lp_childs as $lp_child)
                    <td>
                        <table class="table table-laying-planning" style="margin-bottom: 5px;">
                            <thead>
                                <tr>
                                    <th class="text-left"> COLOR </th>
                                    <th colspan="{{ $lp_child->color_colspan }}">{{ $lp_child->color->color }}</th>
                                    <th style="color:{{ $lp_child->diff_percentage_color }};" >{{ $lp_child->diff_percentage }} %</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="width:80px;">SIZE</td>
                                    @foreach($lp_child->layingPlanningSize as $size)
                                    <td>{{ $size->size->size }}</td>
                                    @endforeach
                                    <td style="width:60px;">TOTAL</td>
                                </tr>
                                <tr>
                                    <td>MI QTY</td>
                                    @foreach($lp_child->layingPlanningSize as $size)
                                    <td>{{ $size->quantity }}</td>
                                    @endforeach
                                    <td>{{ $lp_child->order_qty}}</td>
                                </tr>
                                <tr>
                                    <td>OUTPUT QTY</td>
                                    @foreach($lp_child->cut_qty_per_size as $qty_each_size)
                                    <td>{{ $qty_each_size }}</td>
                                    @endforeach
                                    <td>{{ $lp_child->cut_qty_all_size}}</td>
                                </tr>
                                <tr>
                                    <td>DIFF</td>
                                    @foreach($lp_child->diff_qty_per_size as $diff_each_size)
                                    <td style="{{ ($diff_each_size < 0) ? 'color:red;' : '' }}" >{{ $diff_each_size }}</td>
                                    @endforeach
                                    <td style="text-align: center; {{ ($lp_child->diff_qty_all_size < 0) ? 'color:red;' : '' }}">{{ $lp_child->diff_qty_all_size}}</td>
                                </tr>
                                <tr>
                                    <td>REPLACEMENT</td>
                                    @foreach($lp_child->replacement_qty_per_size as $replacement_each_size)
                                    <td>{{ $replacement_each_size }}</td>
                                    @endforeach
                                    <td>{{ $lp_child->replacement_qty_all_size}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    @endforeach
                @endforeach
            </tbody>
        </table>

        <table class="table table-fabric-consumption">
            <thead>
                <tr>
                    <th width="20px">NO</th>
                    <th>Color</th>
                    <th width="80px">Fab. Req.</th>
                    <th width="80px">Fab. Received</th>
                    <th width="80px">Diff</th>
                    <th width="80px">Fab. Used</th>
                    <th width="80px">Diff (Bal. Fabric)</th>
                </tr>
            </thead>
            <tbody>
                @foreach( $fabric_consumption as $key_lp => $lp)
                <tr>
                    <td>{{ $key_lp + 1 }}</td>
                    <td class="text-left">{{ $lp->color }}</td>
                    <td>{{ $lp->fabric_request }}</td>
                    <td>{{ $lp->fabric_received }}</td>
                    <td>{{ $lp->diff_request_and_received }}</td>
                    <td>{{ $lp->actual_used }}</td>
                    <td>{{ $lp->diff_received_and_used }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table width="100%" style="font-size: 12px; font-family: Times New Roman, Times, serif; font-weight: bold; position: absolute; bottom: 60px;">
            <tr>
                <th width="50%" style="text-align: center;">
                    <p>Prepared by:</p>
                    <p></p>
                    <div style="display:inline-block; text-align:center;">
                    <div style="height: 18px;"></div>
                        <hr style="border: none; height: 1px; background-color: black; margin-top: 0px; margin-bottom: 0px; width: 90px;">
                    </div>
                </th>
                <th width="50%" style="text-align: center;">
                    <p>Authorized by:</p>
                    <p></p>
                    <div style="display:inline-block; text-align:center;">
                    <div style="height: 18px;"></div>
                        <hr style="border: none; height: 1px; background-color: black; margin-top: 0px; margin-bottom: 0px; width: 90px;">
                    </div>
                </th>
                <th width="50%" style="text-align: center;">
                    <p>Approved by:</p>
                    <p></p>
                    <div style="display:inline-block; text-align:center;">
                    <div style="height: 18px;"></div>
                        <hr style="border: none; height: 1px; background-color: black; margin-top: 0px; margin-bottom: 0px; width: 90px;">
                    </div>
                </th>
            </tr>
        </table>
    </div>
</body>
</html>
