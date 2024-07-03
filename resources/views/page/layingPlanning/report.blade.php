<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAYING PLANNING & CUTTING REPORT</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous"> -->
    <style type="text/css">
        
        table.table-bordered > thead > tr > th {
            border: 1px solid black;
        }

        .table thead th {
            text-align: center;
            vertical-align: middle;
            font-size: 8px;
            padding-top: 1 !important;
            padding-bottom: 1 !important;
            padding-left: 0.3 !important;
            padding-right: 0.3 !important;
        }

        .table tbody td {
            border: 1px solid;
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
            font-size: 10px;
            padding-top: 1.5 !important;
            padding-bottom: 1.5 !important;
            padding-left: 0.3 !important;
            padding-right: 0.3 !important;
            margin-bottom: 0px !important;
        }
    </style>
</head>
<body>
    <div>
        <table width="100%">
            <tr>
                <td width="50%" style="font-weight: bold; font-size: 14px;">
                    PT. GHIM LI INDONESIA
                </td>
                <td width="50%" style="text-align: right; font-size: 10px;">
                    RP-GLA-CUT-002-00<br>
                    Rev 00<br>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center; font-weight: bold; font-size: 14px;">
                    LAYING PLANNING & CUTTING REPORT
                    <br>
                    <div style="font-size: 10px;">{{ $laying_planning->serial_number }}</div>
                </td>
            </tr>
        </table>
        <br/>
        <table width="100%" style="font-size: 10px; font-weight: bold; padding-top: 2 !important; padding-bottom: 2 !important; padding-left: 4 !important; padding-right: 4 !important;">
            <tr>
                <td width="6%">Buyer</td>
                <td>{{ $laying_planning->buyer->name }}</td>
                <td></td>
                <td></td>
                <td width="8%">Fabric P/O</td>
                <td>{{ $laying_planning->fabric_po }}</td>
                <td width="10%" style="text-align: right;">Delivery Date:</td>
                <td width="8%" style="text-align: right;">{{ $laying_planning->delivery_date }}</td>
            </tr>

            <tr>
                <td width="6%">Style</td>
                <td>{{ $laying_planning->style->style }}</td>
                <td width="6%">Order Qty</td>
                <td>{{ $laying_planning->order_qty }}</td>
                <td width="8%">Fabric Type</td>
                <td>{{ $laying_planning->fabricType->description }}</td>
                <td width="10%" style="text-align: right;">Plan Date:</td>
                <td width="8%" style="text-align: right;">{{ $laying_planning->plan_date }}</td>
            </tr>
            <tr>
                <td width="6%">GL</td>
                <td>{{ $laying_planning->gl->gl_number }}</td>
                <td width="6%">Total Cut Qty</td>
                <td>{{ $laying_planning->total_cut_qty }}</td>
                <td width="8%">Fabric Cons</td>
                <td>{{ $laying_planning->fabricCons->description }} {{ $laying_planning->fabric_cons_qty }}</td>
                <td></td>
                <td></td>
            </tr>

            <tr>
                <td width="6%">Color</td>
                <td>{{ $laying_planning->color->color }}</td>
                <td width="6%"></td>
                <td></td>
                <td width="8%">Description</td>
                <td>{{ $laying_planning->style->description }}</td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <br/>
        <table class="table table-bordered" style="width:100%; font-size: 10px; font-weight: bold; margin-bottom: 0 !important; padding: 0 !important;">
            <thead>
                <tr>
                    <th rowspan="3">No</br>Laying</br>Sheet</th>
                    <th rowspan="3">Batch #</br>No.</th>
                    <th colspan="{{ $laying_planning->lp_size_count }}">Size/Order</th>
                    <th rowspan="2">Total</th>
                    <th rowspan="3">Yds</br>Qty</th>
                    <th rowspan="3">Marker</br>Code</th>
                    <th rowspan="3">LOT</th>
                    <th colspan="3">Marker</th>
                    <th colspan="{{ $laying_planning->lp_size_count }}">Ratio</th>
                    <th rowspan="3">Lay</br>Qty</th>
                    <th rowspan="3">Cut</br>Qty</th>
                    <th rowspan="3">Date</th>
                    <th rowspan="3">Layer</th>
                    <th rowspan="3">Cutter</th>
                    <th rowspan="3">Emb</br>Print</th>
                    <th rowspan="3">Sew</br>Line</th>
                </tr>
                <tr>
                    @foreach ($laying_planning->layingPlanningSize as $item)
                    <th>{{ $item->size->size }}</th>
                    @endforeach
                    <th rowspan="2">Length</th>
                    <th rowspan="2">Yds</th>
                    <th rowspan="2">Inch</th>
                    @foreach ($laying_planning->layingPlanningSize as $item)
                    <th>{{ $item->size->size }}</th>
                    @endforeach
                </tr>
                <tr>
                    @foreach ($laying_planning->layingPlanningSize as $item)
                    <th>{{ $item->quantity }}</th>
                    @endforeach
                    <th colspan="1">{{ $laying_planning->lp_size_sum_quantity }}</th>
                    @foreach ($laying_planning->layingPlanningSize as $item)
                    <th>{{ $item->quantity }}</th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @foreach ($lp_details as $detail)
                <tr>
                    <td>{{ $detail->table_number }}</td>
                    <td></td>
                    
                    @foreach ($detail->layingPlanningDetailSize as $item)
                    <td>{{ $item->qty_per_size == 0 ? '-' : $item->qty_per_size }}</td>
                    @endforeach
                    
                    <td>{{ $detail->layingPlanningDetailSize->sum('qty_per_size') }}</td>
                    <td>{{ $detail->total_length }}</td>
                    <td>{{ $detail->marker_code }}</td>
                    <td>{{ $detail->table_number }}</td>
                    <td>{{ $detail->marker_length }}</td>
                    <td>{{ $detail->marker_yard }}</td>
                    <td>{{ $detail->marker_inch }}</td>
                    
                    @foreach ($detail->layingPlanningDetailSize as $item)
                        <td>{{ $item->qty_per_size == 0 ? '-' : $item->ratio_per_size }}</td>
                    @endforeach

                    <td>{{ $detail->layer_qty }}</td>
                    <td>{{ $detail->actual_cut_qty }}</td>
                    <td width="40"> {{ $detail->cut_date }} </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @if($loop->last || ($loop->iteration < $lp_details->count() && $detail->marker_code != $lp_details[$loop->iteration]->marker_code))
                    <tr>
                        <td></td>
                        <td></td>
                        @foreach ($laying_planning->layingPlanningSize as $item)
                        <td></td>
                        @endforeach
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        @foreach ($laying_planning->layingPlanningSize as $item)
                        <td></td>
                        @endforeach
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td width="15px !important" height="15px !important"></td>
                    </tr>
                @endif
                @endforeach
                <tr>
                    <td colspan="2">.</td>
                    @foreach ($laying_planning->layingPlanningSize as $item)
                    <td></td>
                    @endforeach
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    @foreach ($laying_planning->layingPlanningSize as $item)
                    <td></td>
                    @endforeach
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                
                <tr>
                    <td colspan="2">Total</td>
                    @foreach($laying_planning->total_per_size as $qty_per_size)
                    <td>{{ $qty_per_size }}</td>
                    @endforeach
                    <td>{{ $laying_planning->total_all_size }}</td>
                    <td>{{ $lp_details->sum('total_length') }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    @foreach ($laying_planning->layingPlanningSize as $size)
                    <td></td>
                    @endforeach
                    <td style="font-size: 11.2px;"> {{ $lp_details->sum('layer_qty') }} </td>
                    <td>{{ $laying_planning->total_cut_qty }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>


                <tr>
                    <td colspan="2">( + / - )</td>
                    @foreach($laying_planning->balance_per_size as $balance_per_size)
                    <td>{{ $balance_per_size }}</td>
                    @endforeach
                    <td>{{ $laying_planning->balance_all_size }}</td>
                    <td>{{ $laying_planning->total_percentage }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    {{-- @foreach ($lp_details->layingPlanning->layingPlanningSize as $item) --}}
                    @foreach ($laying_planning->layingPlanningSize as $item)
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
        <table width="100%" style="font-size: 10px; font-weight: bold; padding-bottom: 28px; margin: 0;">
            <tr>
                <td width="10%">Remark</td>
                <td width="90%">: {{ $laying_planning->remark ?? '-' }}</td>
            </tr>
        </table>

        <table width="100%" style="font-size: 12px; font-weight: bold; position: absolute; bottom: 60px;">
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

        <div style="font-size: 10px; margin-top: 0; text-align: right; position: absolute; bottom: -18; right: 0;">
            Print By : {{ Auth::user()->name }}
        </div>
    </div>
</body>
</html>
