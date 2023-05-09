<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAYING PLANNING & CUTTING REPORT</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">


</head>
<body>
    <div>
        <div class="header-main">
            <div style="float: left; text-align: left; font-weight: bold; font-size: 14px;">
                PT. GHIM LI INDONESIA
            </div>
            <div style="float: right; text-align: right; font-size: 10px;">
                RP-GLA-CUT-002-00<br>
                Rev 00<br>
            </div>
            <br>
            <br>
            <div style="clear:left; text-align: center; font-weight: bold; font-size: 14px;">
            LAYING PLANNING & CUTTING REPORT
                <br>
                <div style="font-size: 10px;">{{ $data->serial_number }}</div>
            </div>

        </div>
        <br/>

        <table width="100%">
            <table width="100%" style="font-size: 10px; font-weight: bold; padding-top: 2 !important; padding-bottom: 2 !important; padding-left: 4 !important; padding-right: 4 !important;">
                <tr>
                    <td width="6%">Buyer</td>
                    <td>{{ $data->buyer->name }}</td>
                    <td></td>
                    <td></td>
                    <td width="8%">Fabric P/O</td>
                    <td>{{ $data->fabric_po }}</td>
                    <td width="10%" style="text-align: right;">Delivery Date:</td>
                    <td width="8%" style="text-align: right;">{{ $data->delivery_date }}</td>
                </tr>

                <tr>
                    <td width="6%">Style</td>
                    <td>{{ $data->style->style }}</td>
                    <td width="6%">Order Qty</td>
                    <td>{{ $data->order_qty }}</td>
                    <td width="8%">Fabric Type</td>
                    <td>{{ $data->fabricType->description }}</td>
                    <td width="10%" style="text-align: right;">Plan Date:</td>
                    <td width="8%" style="text-align: right;">{{ $data->plan_date }}</td>
                </tr>
                <tr>
                    <td width="6%">GL</td>
                    <td>{{ $data->gl->gl_number }}</td>
                    <td width="6%">Total Qty</td>
                    <td>{{ $data->order_qty }}</td>
                    <td width="8%">Fabric Cons</td>
                    <td>{{ $data->fabricCons->description }}</td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td width="6%">Color</td>
                    <td>{{ $data->color->color }}</td>
                    <td width="6%"></td>
                    <td></td>
                    <td width="8%">Description</td>
                    <td>{{ $data->style->description }}</td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
            <br/>
            @php
                $length = count($data->layingPlanningSize);
                $total = 0;
                foreach ($data->layingPlanningSize as $item)
                {
                    $total += $item->quantity;
                }
                $total_detail = 0;
                foreach ($data->layingPlanningSize as $size)
                {
                    $total_detail += $size->quantity;
                }
            @endphp
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Batch #</th>
                        <th colspan="{{ $length }}">Size/Order</th>
                        <th>Total</th>
                        <th>Yds</th>
                        <th>Marker</th>
                        <th colspan="1"></th>
                        <th colspan="3">Marker</th>
                        <th colspan="{{ $length }}">Ratio</th>
                        <th>Lay</th>
                        <th>Cut</th>
                        <th colspan="1"></th>
                        <th>Layer</th>
                        <th>Cutter</th>
                        <th>Emb</th>
                        <th>Sew</th>
                    </tr>
                    <tr>
                        <th>Laying</th>
                        <th>No</th>
                        @foreach ($data->layingPlanningSize as $item)
                        <th>{{ $item->size->size }}</th>
                        @endforeach
                        <th colspan="1"></th>
                        <th>Qty</th>
                        <th>Code</th>
                        <th>LOT</th>
                        <th>Length</th>
                        <th>Yds</th>
                        <th>Inch</th>
                        @foreach ($data->layingPlanningSize as $item)
                        <th>{{ $item->size->size }}</th>
                        @endforeach
                        <th>Qty</th>
                        <th>Qty</th>
                        <th>Date</th>
                        <th colspan="1"></th>
                        <th colspan="1"></th>
                        <th>Print</th>
                        <th>Line</th>
                    </tr>
                    <tr>
                        <th>Sheet</th>
                        <th colspan="1"></th>
                        @foreach ($data->layingPlanningSize as $item)
                        <th>{{ $item->quantity }}</th>
                        @endforeach
                        <th colspan="1">{{ $total }}</th>
                        <th colspan="1"></th>
                        <th colspan="1"></th>
                        <th colspan="1"></th>
                        <th colspan="1"></th>
                        <th colspan="1"></th>
                        <th colspan="1"></th>
                        @foreach ($data->layingPlanningSize as $item)
                        <th>{{ $item->quantity }}</th>
                        @endforeach
                        <th colspan="1"></th>
                        <th colspan="1"></th>
                        <th colspan="1"></th>
                        <th colspan="1"></th>
                        <th colspan="1"></th>
                        <th colspan="1"></th>
                        <th colspan="1"></th>
                </thead>

                <tbody>
                    @foreach ($details as $detail)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td></td>
                        @foreach ($detail->layingPlanning->layingPlanningSize as $item)
                        <td>{{ $item->quantity }}</td>
                        @endforeach
                        <td>{{ $total_detail }}</td>
                        <td>{{ $detail->marker_yard }}</td>
                        <td>{{ $detail->marker_code }}</td>
                        <td>{{ $detail->table_number }}</td>
                        <td>{{ $detail->marker_length }}</td>
                        <td>{{ $detail->marker_yard }}</td>
                        <td>{{ $detail->marker_inch }}</td>
                        @foreach ($detail->layingPlanning->layingPlanningSize as $item)
                        <td>{{ $item->quantity }}</td>
                        @endforeach
                        <td>{{ $detail->layer_qty }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="2">?? PCS FOR SAMPLE</td>
                        @foreach ($data->layingPlanningSize as $item)
                        <!-- total item quantity -->
                        <td><?php
                            $total_per_size = 0;
                            foreach ($details as $detail)
                            {
                                foreach ($detail->layingPlanning->layingPlanningSize as $size)
                                {
                                    if ($size->size_id == $item->size_id)
                                    {
                                        $total_per_size += $size->quantity;
                                    }
                                }
                            }
                            echo $total_per_size;
                        ?></td>
                        @endforeach
                        <!-- sum total qty -->
                        <td><?php
                            $total_all_size = 0;
                            foreach ($details as $detail)
                            {
                                foreach ($detail->layingPlanning->layingPlanningSize as $size)
                                {
                                    $total_all_size += $size->quantity;
                                }
                            }
                            echo $total_all_size;
                        ?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        @foreach ($data->layingPlanningSize as $item)
                        <td></td>
                        @endforeach
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        {{-- @foreach ($details->layingPlanning->layingPlanningSize as $item) --}}
                        @foreach ($data->layingPlanningSize as $item)
                        <td></td>
                        @endforeach
                        {{-- @endforeach --}}
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        {{-- @foreach ($details->layingPlanning->layingPlanningSize as $item) --}}
                        @foreach ($data->layingPlanningSize as $item)
                        <td></td>
                        @endforeach
                        {{-- @endforeach --}} 
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td width="15px !important" height="15px !important"></td>
                    </tr>
                </tbody>
            </table>
            <br>
            <table width="100%" style="margin-top: 20px; font-size: 12px; font-family: Times New Roman, Times, serif">
                <tr>
                    <td width="50%" style="text-align: center;">
                        <p>Prepared by:</p>
                        <p></p>
                        <p>____________________</p>
                    </td>
                    <td width="50%" style="text-align: center;">
                        <p>Authorized by:</p>
                        <p></p>
                        <p>____________________</p>
                    </td>
                    <td width="50%" style="text-align: center;">
                        <p>Approved by:</p>
                        <p></p>
                        <p>____________________</p>
                    </td>
                </tr>
            </table>
            <!-- end signin -->
        </div>
    </body>
</html>

<style type="text/css">
        * {
            font-family: Calibri, san-serif;
        }
        @page {
            margin-top: 1cm;
            margin-left: 1cm;
            margin-bottom: 0cm;
        }
        .table thead th {
            border: 1px solid;
            text-align: center;
            vertical-align: middle;
            font-size: 8px;
            padding-top: 2 !important;
            padding-bottom: 2 !important;
            padding-left: 5 !important;
            padding-right: 5 !important;
        }
        .table tbody td {
            border: 1px solid;
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
            font-size: 8px;
            padding-top: 2 !important;
            padding-bottom: 2 !important;
            padding-left: 5 !important;
            padding-right: 5 !important;
        }
    </style>
