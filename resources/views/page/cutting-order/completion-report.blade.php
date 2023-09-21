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
        @php
            $total_order_qty = 0;

            foreach ($data['layingPlanning'] as $layingPlanning) {
                $total_order_qty += $layingPlanning->order_qty;
            }

            $planning = $data['layingPlanning']->first();
        @endphp
        <table style="font-size: 11px; font-weight: bold; padding-top: 2 !important; padding-bottom: 2 !important; padding-left: 4 !important; padding-right: 4 !important; width: 100% !important;">
            <tr>
                <td>GL#</td>
                <td width="1.5%">:</td>
                <td style="text-align: left;">{{ $planning->gl->gl_number }}</td>
                <td width="13%">TYPE OF FABRIC</td>
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
                <td style="text-align: left;">xxx pcs</td>
                <td width="15%">Actual Marker Length</td>
                <td>:</td>
                <td style="text-align: left;">xx.xx</td>
            </tr>
        </table>
        <br/>

        <!-- "laying_planning": [
            {
                "id": 928,
                "serial_number": "LP-63535-00-CCKVRWGM95COBOS-SK3E085-01",
                "gl_id": 182,
                "style_id": 279,
                "buyer_id": 9,
                "color_id": 563,
                "order_qty": 5602,
                "delivery_date": "2023-11-07",
                "plan_date": "2023-09-14",
                "fabric_po": "160020986",
                "fabric_cons_id": 142,
                "fabric_type_id": 120,
                "fabric_cons_qty": 8,
                "fabric_cons_desc": null,
                "created_at": "2023-09-14T03:26:12.000000Z",
                "updated_at": "2023-09-14T03:26:12.000000Z",
                "gl": {
                    "id": 182,
                    "gl_number": "63535-00",
                    "season": "SP '24",
                    "size_order": "S-XL 1X-3X",
                    "buyer_id": 9,
                    "created_at": "2023-09-14T03:19:20.000000Z",
                    "updated_at": "2023-09-14T03:19:20.000000Z"
                },
                "color": {
                    "id": 563,
                    "color": "CLR/CELERY - K3XHE094",
                    "color_code": "CCKVRWGM",
                    "created_at": "2023-09-14T03:11:34.000000Z",
                    "updated_at": "2023-09-14T03:11:34.000000Z"
                }
            }
        ] -->
        <table class="table table-nota">
            <tbody class="">
                @php
                    $count = 0;
                @endphp
                @for ($i = 0; $i < 10; $i++)
                    @if ($count == 0)
                        <tr>
                    @endif
                    <!-- 'gl', 'style', 'buyer', 'color', 'fabricCons', 'fabricType' -->
                    @if (isset($data['layingPlanning'][$i]))
                        <td style="font-size: 8pt;">{{ $data['layingPlanning'][$i]->gl->gl_number }}</td>
                        <td style="font-size: 8pt;">{{ $data['layingPlanning'][$i]->color->color }}</td>
                    @else
                        <td style="font-size: 8pt;"></td>
                        <td style="font-size: 8pt;"></td>
                    @endif
                    @php
                        $count++;
                    @endphp
                    @if ($count == 2)
                        </tr>
                        @php
                            $count = 0;
                        @endphp
                    @endif
                @endfor
            </tbody>
        </table>

        <table width="100%" style="font-size: 12px; font-family: Times New Roman, Times, serif; font-weight: bold; position: absolute; bottom: 60px;">
            <tr>
                <th width="50%" style="text-align: center;">
                    <p>Prepared by:</p>
                    <p></p>
                    <div style="display:inline-block; text-align:center;">
                        <span>MELDA (58734)</span>
                        <hr style="border: none; height: 1px; background-color: black; margin-top: 0px; margin-bottom: 0px; width: 90px;">
                    </div>
                </th>
                <th width="50%" style="text-align: center;">
                    <p>Authorized by:</p>
                    <p></p>
                    <div style="display:inline-block; text-align:center;">
                        <span>ROBERT (36120)</span>
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
    
    /* @page {
        margin-top: 1cm;
        margin-left: 0.4cm;
        margin-right: 0.4cm;
        margin-bottom: 3.5cm;
    } */
    
    table.table-bordered > thead > tr > th{
        border-top: 1px solid black;
        border-bottom: 1px solid black;
        border-left: 1px solid black;
        border-right: 1px solid black;
    }

    .table thead th {
        text-align: center;
        vertical-align: middle;
        font-size: 8px;
        border: 1px solid;
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
        height:20px;
        padding-top: 1.5 !important;
        padding-bottom: 1.5 !important;
        padding-left: 0.3 !important;
        padding-right: 0.3 !important;
    }
</style>