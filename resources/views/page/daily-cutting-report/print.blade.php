<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cutting Order Record</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <style type="text/css">
    @page {
        margin-top: 1cm;
        margin-left: 0.4cm;
        margin-right: 0.4cm;
        margin-bottom: 1cm;
    }

    .table-nota td,
    .table-nota th {
        padding: 0.25rem 0.25rem;
        font-size: 7pt;
        /* text-align:center; */
        vertical-align: middle;
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
        clear: left;
        /* clear:right; */
        text-align: center;
        font-weight: bold;
        font-size: 14px;
    }

    .serial-number-qr {
        float: right;
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
        border: 1px dashed black;
        vertical-align: middle;
        text-align: center;
        font-weight: bold;
        height: 25px;
        font-size: 6pt;
        margin: 0px !important;
        padding: 0px !important;
        /* max line */
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
                        <th colspan="6">Team</th>
                        <th rowspan="2">Total Qty </br> per day</th>
                        <th rowspan="2">Acumulation </br> (pcs)</th>
                        <th rowspan="2">Completed (%)</th>
                        <th rowspan="2">Replacement for </br> Sewing</th>
                    </tr>
                    <tr>
                        <th rowspan="1">Previous Balance</th>
                        <th rowspan="1">Team 1</th>
                        <th rowspan="1">Team 2</th>
                        <th rowspan="1">Team 3</th>
                        <th rowspan="1">Team 4</th>
                        <th rowspan="1">Team 5</th>
                        <th rowspan="1">Team 6</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($data['laying_planning'] as $key => $layingPlanning)
                    @if ($loop->iteration == 1 || $layingPlanning->buyer->name !=
                    $data['laying_planning'][$key-1]->buyer->name)
                    @php
                    $count_buyer = 0;
                    foreach ($data['laying_planning'] as $key2 => $layingPlanning2) {
                    if ($layingPlanning->buyer->name == $layingPlanning2->buyer->name) {
                    $count_buyer++;
                    }
                    }
                    @endphp
                    <tr>
                        <td rowspan="{{ $count_buyer }}">{{ $layingPlanning->buyer->name }}</td>
                        <td rowspan="{{ $count_buyer }}">{{ $layingPlanning->style->style }}</td>
                        <td rowspan="{{ $count_buyer }}">{{ $layingPlanning->gl->gl_number }}</td>
                        <td style="text-align: left;">{{ $layingPlanning->color->color }}</td>
                        <td>{{ $layingPlanning->order_qty }}</td>
                        <td></td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td><?php
                                $qty = 0;
                                $isTrue = 'false';
                                foreach ($layingPlanning->layingPlanningDetail as $keyLayingPlanningDetail => $layingPlanningDetail) {
                                    if ($layingPlanningDetail->layingPlanningDetailSize->count() > 0) {
                                        $isTrue = 'true';
                                        foreach ($layingPlanningDetail->layingPlanningDetailSize as $keyLayingPlanningDetailSize => $layingPlanningDetailSize) {
                                            $qty += $layingPlanningDetailSize->ratio_per_size * $layingPlanningDetail->layer_qty;
                                        }
                                    }
                                }
                                echo $qty;
                            ?></td>
                        <td><?php
                                $accumulation = 0;
                                foreach ($layingPlanning->layingPlanningDetail as $keyLayingPlanningDetail => $layingPlanningDetail) {
                                    foreach ($data['cutting_order_record_detail'] as $keyCuttingOrderRecordDetail => $cuttingOrderRecordDetail) {
                                        foreach ($cuttingOrderRecordDetail->cuttingOrderRecord as $keyCuttingOrderRecord => $cuttingOrderRecord) {
                                            $accumulation += $cuttingOrderRecordDetail->layer;
                                        }
                                    }
                                }
                                echo $accumulation;
                            ?></td>
                        <td><?php
                                foreach ($layingPlanning->layingPlanningDetail as $keyLayingPlanningDetail => $layingPlanningDetail) {
                                    foreach ($data['cutting_order_record_detail'] as $keyCuttingOrderRecordDetail => $cuttingOrderRecordDetail) {
                                        foreach ($cuttingOrderRecordDetail->cuttingOrderRecord as $keyCuttingOrderRecord => $cuttingOrderRecord) {
                                            $accumulation += $cuttingOrderRecordDetail->layer;
                                        }
                                    }
                                }
                                echo $accumulation / $layingPlanning->order_qty * 100;
                            ?>%</td>
                        <td></td>
                    </tr>
                    @else
                    <tr>
                        <td style="text-align: left;">{{ $layingPlanning->color->color }}</td>
                        <td>{{ $layingPlanning->order_qty }}</td>
                        <td></td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td><?php
                                $qty = 0;
                                $isTrue = 'false';
                                foreach ($layingPlanning->layingPlanningDetail as $keyLayingPlanningDetail => $layingPlanningDetail) {
                                    if ($layingPlanningDetail->layingPlanningDetailSize->count() > 0) {
                                        $isTrue = 'true';
                                        foreach ($layingPlanningDetail->layingPlanningDetailSize as $keyLayingPlanningDetailSize => $layingPlanningDetailSize) {
                                            $qty += $layingPlanningDetailSize->ratio_per_size * $layingPlanningDetail->layer_qty;
                                        }
                                    }
                                }
                                echo $qty;
                            ?></td>
                        <td><?php
                                $accumulation = 0;
                                foreach ($layingPlanning->layingPlanningDetail as $keyLayingPlanningDetail => $layingPlanningDetail) {
                                    foreach ($data['cutting_order_record_detail'] as $keyCuttingOrderRecordDetail => $cuttingOrderRecordDetail) {
                                        foreach ($cuttingOrderRecordDetail->cuttingOrderRecord as $keyCuttingOrderRecord => $cuttingOrderRecord) {
                                            $accumulation += $cuttingOrderRecordDetail->layer;
                                        }
                                    }
                                }
                                echo $accumulation;
                            ?></td>
                        <td><?php
                                foreach ($layingPlanning->layingPlanningDetail as $keyLayingPlanningDetail => $layingPlanningDetail) {
                                    foreach ($data['cutting_order_record_detail'] as $keyCuttingOrderRecordDetail => $cuttingOrderRecordDetail) {
                                        foreach ($cuttingOrderRecordDetail->cuttingOrderRecord as $keyCuttingOrderRecord => $cuttingOrderRecord) {
                                            $accumulation += $cuttingOrderRecordDetail->layer;
                                        }
                                    }
                                }
                                echo $accumulation / $layingPlanning->order_qty * 100;
                            ?>%</td>
                        <td></td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>

            </br>
            </br>

            <table width="100%" style="font-size: 12px; font-family: Times New Roman, Times, serif; font-weight: bold;">
                <tr>
                    <td width="50%" style="text-align: center;">
                        <p>Prepared by:</p>
                        <p></p>
                        <div style="display:inline-block; text-align:center;">
                            <span>MELDA (58734)</span>
                            <hr
                                style="border: none; height: 1px; background-color: black; margin-top: 0px; margin-bottom: 0px; width: 90px;">
                        </div>
                    </td>
                    <td width="50%" style="text-align: center;">
                        <p>Authorized by:</p>
                        <p></p>
                        <div style="display:inline-block; text-align:center;">
                            <span>ROBERT (36120)</span>
                            <hr
                                style="border: none; height: 1px; background-color: black; margin-top: 0px; margin-bottom: 0px; width: 90px;">
                        </div>
                    </td>
                    <td width="50%" style="text-align: center;">
                        <p>Approved by:</p>
                        <p></p>
                        <div style="display:inline-block; text-align:center;">
                            <span>ZONDRA (30950)</span>
                            <hr
                                style="border: none; height: 1px; background-color: black; margin-top: 0px; margin-bottom: 0px; width: 90px;">
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>