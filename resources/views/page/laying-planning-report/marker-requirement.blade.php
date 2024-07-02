<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $filename }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style type="text/css">
        
        table.table-bordered > thead > tr > th ,
        table.table-bordered > tbody > tr > td ,
        table.table-bordered > tfoot > tr > td {
            border: 1px solid black;
        }

        .table thead th {
            text-align: center;
            vertical-align: middle;
            font-size: 12px;
            padding-top: 4 !important;
            padding-bottom: 4 !important;
            padding-left: 2 !important;
            padding-right: 2 !important;
            background-color: #dddddd;
        }
        
        .table tbody td {
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
            font-size: 11px;
            padding-top: 4 !important;
            padding-bottom: 4 !important;
            padding-left: 2 !important;
            padding-right: 2 !important;
        }

        .table tfoot td {
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
            font-size: 12px;
            padding-top: 4 !important;
            padding-bottom: 4 !important;
            padding-left: 2 !important;
            padding-right: 2 !important;
            background-color: #cccccc;
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
            </tr>
            <tr>
                <td colspan="2" style="text-align: center; font-weight: bold; font-size: 18px;">
                    Marker Requirement Report
                </td>
            </tr>
        </table>
        <br/>
        <table width="100%" style="font-size: 11px; font-weight: bold;">
            <tr style="font-size: 12px;">
                <td colpsan="1">LP Serial Serial Number</td>
                <td colpsan="3"> : {{ $layingPlanning->serial_number }}</td>
            </tr>
            <tr>
                <td style="width:15%;"> Buyer</td>
                <td style="width:30%;"> : {{ $layingPlanning->buyer->name }}</td>
                <td style="width:10%;"> Fabric P/O</td>
                <td style="width:auto;"> : {{ $layingPlanning->fabric_po }}</td>
            </tr>

            <tr>
                <td style="width:15%;"> Style</td>
                <td style="width:30%;"> : {{ $layingPlanning->style->style }}</td>
                <td style="width:10%;"> Fabric Type</td>
                <td style="width:auto;"> : {{ $layingPlanning->fabricType->description }}</td>
            </tr>
            <tr>
                <td style="width:15%;"> GL</td>
                <td style="width:30%;"> : {{ $layingPlanning->gl->gl_number }}</td>
                <td style="width:10%;"> Fabric Cons</td>
                <td style="width:auto;"> : {{ $layingPlanning->fabricCons->description }} {{ $layingPlanning->fabric_cons_qty }} </td>
            </tr>

            <tr>
                <td style="width:15%;"> Color</td>
                <td style="width:30%;"> : {{ $layingPlanning->color->color }}</td>
                <td style="width:10%;"> Description</td>
                <td style="width:auto;"> : {{ $layingPlanning->style->description }}</td>
            </tr>
        </table>
        <br/>

        <table class="table table-bordered" style="width:100%;">
            <thead>
                <tr>
                    <th rowspan="2" colspan="1" style="width:auto">No</th>
                    <th rowspan="2" colspan="1" style="width:40%">Marker Code</th>
                    <th rowspan="2" colspan="1" style="width:10%">Marker Qty</th>
                    <th rowspan="1" colspan="3" style="width:auto">Marker</th>
                    <th rowspan="1" colspan="{{ $layingPlanning->layingPlanningSize->count() }}" style="width:auto">Ratio</th>
                    <th rowspan="2" colspan="1" style="width:10%">Total Length <br> (YDs)</th>
                </tr>
                <tr>
                    <th>Length</th>
                    <th>Yds</th>
                    <th>Inch</th>
                    @foreach($layingPlanning->layingPlanningSize as $layingPlanningSize)
                        <th>{{ $layingPlanningSize->size->size }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($uniqueMarker as $key => $marker)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $marker->marker_code }}</td>
                        <td>{{ $marker->marker_qty }}</td>
                        <td>{{ $marker->marker_length }}</td>
                        <td>{{ $marker->marker_yard }}</td>
                        <td>{{ $marker->marker_inch }}</td>
                        @foreach($layingPlanning->layingPlanningSize as $layingPlanningSize)
                            <td>{{ $marker->size_ratio[$layingPlanningSize->size->id] }}</td>
                        @endforeach
                        <td>{{ $marker->total_length }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" >Total</td>
                    <td colspan="1" >{{ $totalAllMarker->total_all_qty }}</td>
                    <td colspan="{{ 3 + $layingPlanning->layingPlanningSize->count() }}" ></td>
                    <td colspan="1" >{{ $totalAllMarker->total_all_length }}</td>
                </tr>
            </tfoot>
        </table>


        <div style="font-size: 11px; margin-top: 0; text-align: right; position: absolute; bottom: 0; right: 0;">
            <p style="padding:1px; margin:1px;" >Print by : {{ Auth::user()->name }}</p>
            <p style="padding:1px; margin:1px;" >Print date : {{ $printedDate }}</p>
        </div>
    </div>
</body>
</html>
