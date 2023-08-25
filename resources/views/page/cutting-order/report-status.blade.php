<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cutting Order Status</title>

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
            font-size: 9pt;
            margin: 0px !important;
            padding: 0px !important;
        }
        .table-nota tbody td {
            border: 1px solid;
            vertical-align: middle;
            text-align: center;
            font-weight: bold;
            font-size:8pt;
            padding-top: 1 !important;
            padding-bottom: 1 !important;
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
                STATUS CUTTING ORDER REPORT
                <br>
                @if ($data['gl_number'] == null)
                <div class="subtitle-nota"></div>
                @else
                <div class="subtitle-nota">GL : {{ $data['gl_number'] }}</div>
                @endif
                <div class="subtitle-nota"></div>
            </div>

        </div>

        <table class="table">
            <tbody>
                <tr>
                    <td style="font-size: 9pt; font-weight: bold; border: 0px solid !important; padding-top: 1.5 !important; padding-bottom: 1.5 !important; padding-left: 0.3 !important; padding-right: 0.3 !important;">Date Start</td>
                    <td style="font-size: 9pt; font-weight: bold; border: 0px solid !important; padding-top: 1.5 !important; padding-bottom: 1.5 !important; padding-left: 0.3 !important; padding-right: 0.3 !important;">:</td>
                    <td style="font-size: 9pt; font-weight: bold; border: 0px solid !important; padding-top: 1.5 !important; padding-bottom: 1.5 !important; padding-left: 0.3 !important; padding-right: 0.3 !important;">{{ $data['date_start'] }}</td>
                    <td style="font-size: 9pt; font-weight: bold; border: 0px solid !important; padding-top: 1.5 !important; padding-bottom: 1.5 !important; padding-left: 0.3 !important; padding-right: 0.3 !important;">Date End</td>
                    <td style="font-size: 9pt; font-weight: bold; border: 0px solid !important; padding-top: 1.5 !important; padding-bottom: 1.5 !important; padding-left: 0.3 !important; padding-right: 0.3 !important;">:</td>
                    <td style="font-size: 9pt; font-weight: bold; border: 0px solid !important; padding-top: 1.5 !important; padding-bottom: 1.5 !important; padding-left: 0.3 !important; padding-right: 0.3 !important;">{{ $data['date_end'] }}</td>
                </tr>
                @if ($data['status_layer'] == null || $data['status_layer'] == null)
                @else
                <tr>
                    @if ($data['status_layer'] == null)
                        <td style="font-size: 9pt; font-weight: bold; border: 0px solid !important; padding-top: 1.5 !important; padding-bottom: 1.5 !important; padding-left: 0.3 !important; padding-right: 0.3 !important;"></td>
                        <td style="font-size: 9pt; font-weight: bold; border: 0px solid !important; padding-top: 1.5 !important; padding-bottom: 1.5 !important; padding-left: 0.3 !important; padding-right: 0.3 !important;"></td>
                        <td style="font-size: 9pt; font-weight: bold; border: 0px solid !important; padding-top: 1.5 !important; padding-bottom: 1.5 !important; padding-left: 0.3 !important; padding-right: 0.3 !important;"></td>
                    @else
                        <td style="font-size: 9pt; font-weight: bold; border: 0px solid !important; padding-top: 1.5 !important; padding-bottom: 1.5 !important; padding-left: 0.3 !important; padding-right: 0.3 !important;">Status Layer</td>
                        <td style="font-size: 9pt; font-weight: bold; border: 0px solid !important; padding-top: 1.5 !important; padding-bottom: 1.5 !important; padding-left: 0.3 !important; padding-right: 0.3 !important;">:</td>
                        <td style="font-size: 9pt; font-weight: bold; border: 0px solid !important; padding-top: 1.5 !important; padding-bottom: 1.5 !important; padding-left: 0.3 !important; padding-right: 0.3 !important;">{{ $data['status_layer'] ?? '-' }}</td>
                    @endif
                    @if ($data['status_cut'] == null)
                        <td style="font-size: 9pt; font-weight: bold; border: 0px solid !important; padding-top: 1.5px !important; padding-bottom: 1.5px !important; padding-left: 0.3px !important; padding-right: 0.3px !important;"></td>
                        <td style="font-size: 9pt; font-weight: bold; border: 0px solid !important; padding-top: 1.5px !important; padding-bottom: 1.5px !important; padding-left: 0.3px !important; padding-right: 0.3px !important;"></td>
                        <td style="font-size: 9pt; font-weight: bold; border: 0px solid !important; padding-top: 1.5px !important; padding-bottom: 1.5px !important; padding-left: 0.3px !important; padding-right: 0.3px !important;"></td>
                    @else
                        <td style="font-size: 9pt; font-weight: bold; border: 0px solid !important; padding-top: 1.5 !important; padding-bottom: 1.5 !important; padding-left: 0.3 !important; padding-right: 0.3 !important;">Status Cut</td>
                        <td style="font-size: 9pt; font-weight: bold; border: 0px solid !important; padding-top: 1.5 !important; padding-bottom: 1.5 !important; padding-left: 0.3 !important; padding-right: 0.3 !important;">:</td>
                        <td style="font-size: 9pt; font-weight: bold; border: 0px solid !important; padding-top: 1.5 !important; padding-bottom: 1.5 !important; padding-left: 0.3 !important; padding-right: 0.3 !important;">{{ $data['status_cut'] ?? '-' }}</td>
                    @endif
                </tr>
                @endif
            </tbody>
        </table>
            <div class="body-nota">
                <table class="table table-nota">
                    <thead class="">
                        <tr>
                            <th>G/L Number</th>
                            <th>Serial Number</th>
                            <th>Color</th>
                            <th>Style</th>
                            <th>Layer</th>
                            @if ($data['status_layer'] == null || $data['status_layer'] == null)
                                <th>Status Layer</th>
                                <th>Status Cut</th>
                                <th>Date Layer</th>
                                <th>Date Cut</th>
                            @else
                                <th>Status Layer</th>
                                <th>Status Cut</th>
                                <th>Date Layer</th>
                                <th>Date Cut</th>
                            @endif
                            
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($data['cuttingOrderRecord'] as $item)
                        <tr>
                            <td>{{ $item->layingPlanningDetail->layingPlanning->gl->gl_number }}</td>
                            <td>{{ $item->serial_number }}</td>
                            <td>{{ $item->layingPlanningDetail->layingPlanning->color->color }}</td>
                            <td>{{ $item->layingPlanningDetail->layingPlanning->style->style }}</td>
                            <td><?php $total_layer = 0; ?>
                                @foreach ($item->cuttingOrderRecordDetail as $detail)
                                    <?php $total_layer += $detail->layer; ?>
                                @endforeach
                                {{ $total_layer }}
                            </td>
                            
                            <td>
                                @if ($item->statusLayer->name == 'not completed')
                                    Belum Layer
                                @elseif ($item->statusLayer->name == 'completed')
                                    Sudah Layer
                                @else
                                    Over Layer
                                @endif
                            </td>
                            <td>
                                @if ($item->statusCut->name == 'belum')
                                    Belum Potong
                                @else
                                    Sudah Potong
                                @endif
                            </td>
                            <td><?php
                                foreach ($item->cuttingOrderRecordDetail as $detail) {
                                    if ($detail != null) {
                                        echo date('d/M/Y', strtotime($detail->updated_at));
                                        break;
                                    }
                                }
                            ?></td>
                            <td><?php
                                foreach ($item->cuttingOrderRecordDetail as $detail) {
                                    if ($detail != null) {
                                        echo date('d/M/Y', strtotime($item->updated_at));
                                        break;
                                    }
                                }
                            ?></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
    </div>
</body>
</html>