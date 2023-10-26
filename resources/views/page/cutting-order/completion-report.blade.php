<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CUTTING COMPLETION REPORT</title>
</head>
<body>
    <div>
        <table width="100%">
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
                    <span style="font-size: 12px;">{{ $data['start_cut'] }} - {{ $data['finish_cut'] }}</span>
                </td>
            </tr>
        </table>
        <br/>
        <br/>
        <br/>
        @php
            $total_order_qty = 0;

            foreach ($data['layingPlanning'] as $layingPlanning) {
                $total_order_qty += $layingPlanning->order_qty;
            }

            $planning = $data['layingPlanning']->first();
        @endphp
        <table style="font-size: 12px; font-weight: bold; padding-top: 2 !important; padding-bottom: 2 !important; padding-left: 4 !important; padding-right: 4 !important; width: 100% !important;">
            <tr>
                <td width="5%">GL#</td>
                <td width="1.5%">:</td>
                <td style="text-align: left;">{{ $planning->gl->gl_number }}</td>
                <td width="11%">TYPE OF FABRIC</td>
                <td width="1.5%">:</td>
                <td style="text-align: left;">{{ $planning->fabricType->name }}</td>
                <td>DATE</td>
                <td width="1.5%">:</td>
                <td style="text-align: left;">{{ $planning->plan_date }}</td>
            </tr>

            <tr>
                <td>PO. NO</td>
                <td>:</td>
                <td style="text-align: left;">{{ $planning->fabric_po }}</td>
                <td>FABRIC.CON'S</td>
                <td>:</td>
                <td style="text-align: left;">{{ $planning->fabricCons->name }}</td>
                <td>DELIVERY DATE</td>
                <td>:</td>
                <td style="text-align: left;">{{ $planning->delivery_date }}</td>
                
            </tr>
            
            <tr>
                <td width="6%">BUYER</td>
                <td>:</td>
                <td style="text-align: left;">{{ $planning->buyer->name }}</td>
                <td>QTY REQ</td>
                <td>:</td>
                <td style="text-align: left;">{{ $total_order_qty }} pcs</td>
                <td>PO Marker</td>
                <td>:</td>
                <td style="text-align: left;">xx.xx</td>
            </tr>

            <tr>
                <td>STYLE</td>
                <td>:</td>
                <td style="text-align: left;">{{ $planning->style->style }}</td>
                <td>DIFF</td>
                <td>:</td>
                <td style="text-align: left;">
                    @php
                        $result = 0;
                        foreach ($data['layingPlanning'] as $layingPlanning)
                        {
                            foreach ($layingPlanning->layingPlanningSize as $lps)
                            {
                                $diff_order_cut_qty_per_size = 0;
                                foreach ($layingPlanning->layingPlanningDetail as $lpd)
                                {
                                    foreach ($lpd->layingPlanningDetailSize as $lps2)
                                    {
                                        if ($lps2->size_id == $lps->size_id)
                                        {
                                            if ($lpd->cuttingOrderRecord == null)
                                            {
                                                $diff_order_cut_qty_per_size += 0;
                                            }
                                            else
                                            {
                                                foreach ($lpd->cuttingOrderRecord->cuttingOrderRecordDetail as $cord)
                                                {
                                                    $diff_order_cut_qty_per_size += $cord->layer * $lps2->ratio_per_size;
                                                }
                                            }
                                        }
                                    }
                                }
                                $result += $diff_order_cut_qty_per_size - $lps->quantity;
                            }
                        }
                        echo $result;
                    @endphp
                </td>
                <td width="11%">Actual Marker Length</td>
                <td>:</td>
                <td style="text-align: left;">xx.xx</td>
            </tr>
        </table>
        <br/>

        @php
            $maxColumnCount = 2;
            $layingPlanning = $data['layingPlanning'];
            $rowCount = ceil(count($layingPlanning) / $maxColumnCount);
        @endphp

        <table width="100%" style="font-size: 12px; font-family: Times New Roman, Times, serif; font-weight: bold;">
            <tbody>
                @for ($row = 0; $row < $rowCount; $row++)
                    <tr>
                        @for ($col = 0; $col < $maxColumnCount; $col++)
                            @php
                                $index = $row * $maxColumnCount + $col;
                            @endphp

                            @if ($index < count($layingPlanning))
                                @php
                                    $currentPlanning = $layingPlanning[$index];
                                    $sizeCount = count($currentPlanning->layingPlanningSize);
                                @endphp
                                
                                <td>
                                    <table class="table table-completion" style="width: 100%;">
                                        <tbody>
                                            <tr>
                                                @if ($col == 0)
                                                    <td style="text-align: left; width: 12%;">COLOR</td>
                                                @endif
                                                <td colspan="{{ $sizeCount + 1 }}" style="text-align: center;">{{ $currentPlanning->color->color }}</td>
                                            </tr>
                                            <tr>
                                                @if ($col == 0)
                                                    <td style="text-align: left;">SIZE</td>
                                                @endif
                                                @foreach ($currentPlanning->layingPlanningSize as $lps)
                                                    <td width="18%">{{ $lps->size->size }}</td>
                                                @endforeach
                                                <td style="text-align: center;">TOTAL</td>
                                            </tr>
                                            <tr>
                                                @if ($col == 0)
                                                    <td style="text-align: left;">MI QTY</td>
                                                @endif
                                                @foreach ($currentPlanning->layingPlanningSize as $lps)
                                                    <td>{{ $lps->quantity }}</td>
                                                @endforeach
                                                <td style="text-align: center;">
                                                    @php
                                                        $total_qty = 0;
                                                        foreach ($currentPlanning->layingPlanningSize as $lps)
                                                        {
                                                            $total_qty += $lps->quantity;
                                                        }
                                                    @endphp
                                                    {{ $total_qty }}
                                                </td>
                                            </tr>
                                            <tr>
                                                @if ($col == 0)
                                                    <td style="text-align: left;">CUT QTY</td>
                                                @endif
                                                @foreach ($currentPlanning->layingPlanningSize as $lps)
                                                    @php
                                                        $cut_qty = 0;
                                                        foreach ($currentPlanning->layingPlanningDetail as $lpd)
                                                        {
                                                            foreach ($lpd->layingPlanningDetailSize as $lps2)
                                                            {
                                                                if ($lps2->size_id == $lps->size_id)
                                                                {
                                                                    if ($lpd->cuttingOrderRecord == null)
                                                                    {
                                                                        $cut_qty += 0;
                                                                    }
                                                                    else
                                                                    {
                                                                        foreach ($lpd->cuttingOrderRecord->cuttingOrderRecordDetail as $cord)
                                                                        {
                                                                            $cut_qty += $cord->layer * $lps2->ratio_per_size;
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    @endphp
                                                    <td>{{ $cut_qty }}</td>
                                                @endforeach
                                                <td style="text-align: center;">
                                                    @php
                                                        $total_cut_qty = 0;
                                                        foreach ($currentPlanning->layingPlanningDetail as $lpd)
                                                        {
                                                            foreach ($lpd->layingPlanningDetailSize as $lps2)
                                                            {
                                                                if ($lpd->cuttingOrderRecord == null)
                                                                {
                                                                    $cut_qty += 0;
                                                                }
                                                                else
                                                                {
                                                                    foreach ($lpd->cuttingOrderRecord->cuttingOrderRecordDetail as $cord)
                                                                    {
                                                                        $total_cut_qty += $cord->layer * $lps2->ratio_per_size;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    @endphp
                                                    {{ $total_cut_qty }}
                                                </td>
                                            </tr>
                                            <tr>
                                                @if ($col == 0)
                                                    <td style="text-align: left;">DIFF</td>
                                                @endif
                                                @foreach ($currentPlanning->layingPlanningSize as $lps)
                                                    @php
                                                        $diff_order_cut_qty_per_size = 0;
                                                        foreach ($currentPlanning->layingPlanningDetail as $lpd)
                                                        {
                                                            foreach ($lpd->layingPlanningDetailSize as $lps2)
                                                            {
                                                                if ($lps2->size_id == $lps->size_id)
                                                                {
                                                                    if ($lpd->cuttingOrderRecord == null)
                                                                    {
                                                                        $diff_order_cut_qty_per_size += 0;
                                                                    }
                                                                    else
                                                                    {
                                                                        foreach ($lpd->cuttingOrderRecord->cuttingOrderRecordDetail as $cord)
                                                                        {
                                                                            $diff_order_cut_qty_per_size += $cord->layer * $lps2->ratio_per_size;
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    @endphp
                                                    @if(($diff_order_cut_qty_per_size - $lps->quantity) < 0)
                                                        <td style="color:red;">{{ $diff_order_cut_qty_per_size - $lps->quantity }}</td>
                                                    @else
                                                        <td>{{ $diff_order_cut_qty_per_size - $lps->quantity }}</td>
                                                    @endif
                                                @endforeach
                                                <td style="text-align: center;">
                                                    @php
                                                        $total_ratio_layer = 0;
                                                        $total_diff_order_cut_qty = 0;
                                                        foreach ($currentPlanning->layingPlanningDetail as $lpd)
                                                        {
                                                            foreach ($lpd->layingPlanningDetailSize as $lps2)
                                                            {
                                                                if ($lpd->cuttingOrderRecord == null)
                                                                {
                                                                    $cut_qty += 0;
                                                                }
                                                                else
                                                                {
                                                                    foreach ($lpd->cuttingOrderRecord->cuttingOrderRecordDetail as $cord)
                                                                    {
                                                                        $total_ratio_layer += $cord->layer * $lps2->ratio_per_size;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        $total_diff_order_cut_qty = $total_ratio_layer - $total_qty;
                                                    @endphp
                                                    {{ $total_diff_order_cut_qty }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            @else
                                <td></td>
                            @endif
                        @endfor
                    </tr>
                @endfor
            </tbody>
        </table>
        <br/>
        <table class="table table-completion" style="width:100%; font-size: 10px; font-weight: bold; margin-bottom: 0 !important; padding: 0 !important;">
            <thead>
                <tr>
                    <th width="2%" style="text-align: center;">NO</th>
                    <th>Color</th>
                    <th width="6%">Fab. Req.</th>
                    <th width="6%">Fab. Received</th>
                    <th width="6%">Diff</th>
                    <th width="6%">Fab. Used</th>
                    <th width="6%">Diff (Bal. Fabric)</th>
                </tr>
            </thead>
            
            <tbody>
                @foreach ($data['layingPlanning'] as $layingPlanning)
                    <tr>
                        <td style="text-align: center;">{{ $loop->iteration }}</td>
                        <td style="text-align: left; padding-left: 6px;">{{ $layingPlanning->color->color }}</td>
                        <td><?php
                            $total_fab_req = 0;
                            foreach ($layingPlanning->layingPlanningDetail as $lpd)
                            {
                                $total_fab_req += $lpd->total_length;
                            }
                            echo $total_fab_req;
                        ?></td>
                        <td><?php
                            $total_fab_received = 0;
                            foreach ($layingPlanning->layingPlanningDetail as $lpd)
                            {
                                if ($lpd->cuttingOrderRecord == null)
                                {
                                    $total_fab_received += 0;
                                }
                                else
                                {
                                    foreach ($lpd->cuttingOrderRecord->cuttingOrderRecordDetail as $cord)
                                    {
                                        $total_fab_received += $cord->yardage;
                                    }
                                }
                            }
                            echo $total_fab_received;
                        ?></td> 
                        <td>
                            {{ $total_fab_received - $total_fab_req }}
                        </td>
                        <td><?php
                            $total_fab_used = 0;
                            foreach ($layingPlanning->layingPlanningDetail as $lpd)
                            {
                                if ($lpd->cuttingOrderRecord == null)
                                {
                                    $total_fab_used += 0;
                                }
                                else
                                {
                                    foreach ($lpd->cuttingOrderRecord->cuttingOrderRecordDetail as $cord)
                                    {
                                        $actual = $cord->layer * ($lpd->marker_yard + ($lpd->marker_inch / 36));
                                        $actual = number_format($actual, 2, '.', '');
                                        $total_fab_used += $actual;
                                    }
                                }
                            }
                            echo $total_fab_used;
                        ?></td>
                        <td>
                            {{ $total_fab_received - $total_fab_used }}
                        </td>
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

<style type="text/css">
    * {
        font-family: Calibri, san-serif;
    }

    .table-completion {
        font-size: 11px;
        font-family: Times New Roman, Times, serif;
        font-weight: bold;
        border-collapse: collapse;
    }

    .table-completion td {
        padding: 2px;
    }

    .table-completion th {
        padding: 2px;
    }

    .table-completion tr {
        border: 1px solid black;
    }

    .table-completion tr td {
        border: 1px solid black;
        text-align: center;
    }

    .table-completion tr th {
        border: 1px solid black;
        padding: 2px;
    }

    .table-fab-issue{
        
    }
    
</style>