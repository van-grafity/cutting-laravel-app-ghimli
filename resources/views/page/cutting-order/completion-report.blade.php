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
                    RP-GLA-CUT-002-00<br>
                    Rev 00<br>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center; font-weight: bold; font-size: 14px;">
                    CUTTING COMPLETION REPORT
                    <br>
                    <div style="font-size: 10px;">{{ $data->serial_number }}</div>
                </td>
            </tr>
        </table>
        <br/>
        <table width="100%" style="font-size: 10px; font-weight: bold; padding-top: 2 !important; padding-bottom: 2 !important; padding-left: 4 !important; padding-right: 4 !important;">
            <tr>
                <td width="6%">GL#</td>
                <td>63063-00</td>
                <td></td>
                <td></td>
                <td width="8%">Fabric P/O</td>
                <td>{{ $data->fabric_po }}</td>
                <td width="10%" style="text-align: right;">Delivery Date:</td>
                <td width="8%" style="text-align: right;">
                    {{ date('d-M-Y', strtotime($data->delivery_date)) }}
                </td>
            </tr>

            <tr>
                <td width="6%">PO. NO</td>
                <td>100049955</td>
                <td width="6%">Order Qty</td>
                <td>{{ $data->order_qty }}</td>
                <td width="8%">Fabric Type</td>
                <td>{{ $data->fabricType->description }}</td>
                <td width="10%" style="text-align: right;">Plan Date:</td>
                <td width="8%" style="text-align: right;">
                    {{ date('d-M-Y', strtotime($data->plan_date)) }}
                </td>
            </tr>
            <tr>
                <td width="6%">BUYER</td>
                <td>AMAZON</td>
                <td width="6%">Total Qty</td>
                <td>{{ $data->order_qty }}</td>
                <td width="8%">Fabric Cons</td>
                <td>{{ $data->fabricCons->description }} {{ $data->fabric_cons_qty }}</td>
                <td></td>
                <td></td>
            </tr>

            <tr>
                <td width="6%">STYLE</td>
                <td>202-23C111595</td>
                <td width="6%"></td>
                <td></td>
                <td width="8%">Description</td>
                <td>{{ $data->style->description }}</td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <br/>

        <table width="100%" style="font-size: 12px; font-family: Times New Roman, Times, serif; font-weight: bold; position: absolute; bottom: 60px;">
            <tr>
                <td width="50%" style="text-align: center;">
                    <p>Prepared by:</p>
                    <p></p>
                    <div style="display:inline-block; text-align:center;">
                        <span>MELDA (58734)</span>
                        <hr style="border: none; height: 1px; background-color: black; margin-top: 0px; margin-bottom: 0px; width: 90px;">
                    </div>
                </td>
                <td width="50%" style="text-align: center;">
                    <p>Authorized by:</p>
                    <p></p>
                    <div style="display:inline-block; text-align:center;">
                        <span>ROBERT (36120)</span>
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