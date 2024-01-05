<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUMMARY CUTTING BY GROUP</title>
    <!-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

</head>
<body>
    <div>
        <table width="100%">
            <tr>
                <td colspan="2" style="text-align: center; font-weight: bold; font-size: 14px;">
                    SUMMARY CUTTING {{ strtoupper($group->group_name) }}
                    <br/>
                    <span style="font-size: 12px;">{{ $date_start }} - {{ $date_end }}</span>
                </td>
            </tr>
        </table>
        <br/>
        <br/>

        
        <table class="table table-bordered" style="width:100%; font-size: 10px; font-weight: bold;">
            <thead>
                <tr>
                    <th rowspan="2" width="2%">No</th>
                    <th rowspan="2" width="6%">Gl Number</th>
                    <th rowspan="2" width="7%">No</br>Laying</br>Sheet</th>
                    <th rowspan="2">COR Serial No.</th>
                    <th rowspan="2" width="6.2%">Date</th>
                    <th rowspan="2">Color</th>
                    <th rowspan="2" hidden>Size/Ratio</th>
                    <th colspan="{{ count($all_size_in_summary) }}">Size/Ratio</th>
                    <th rowspan="2" width="3.2%">Layer</th>
                    <th rowspan="2" width="3.6%">Pcs</th>
                    <th rowspan="2" width="3.2%">Dz</th>
                </tr>
                <tr>
                    @foreach ($all_size_in_summary as $size)
                        <th rowspan="1" width="2%">{{ $size->size }}</th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @php $number_counter = 1 @endphp
                @foreach ($cutting_summary as $key_gl => $summary_by_gl)
                    @foreach($summary_by_gl->cor_list as $key_cor => $cor)
                    <tr>
                        <td>{{ $number_counter++ }}</td>
                        <td>{{ $summary_by_gl->gl->gl_number }}</td>
                        <td>{{ $cor->no_laying_sheet }}</td>
                        <td>{{ $cor->cor_serial_number }}</td>
                        <td>{{ $cor->shift_date }}</td>
                        <td style="text-align: left; padding-left:10px !important;">{{ $cor->color }}</td>
                        @foreach($cor->ratio_per_size_in_summary as $key_ratio_size => $ratio_per_size)
                            <td>{{ $ratio_per_size }}</td>
                        @endforeach
                        <td>{{ $cor->cor_layer }}</td>
                        <td>{{ $cor->cor_pcs }}</td>
                        <td>{{ $cor->cor_dozen }}</td>
                    </tr>
                    @endforeach
                    <tr style="background-color: #d9d9d9;">
                        <td colspan="{{ count($all_size_in_summary) + 7 }}" style="text-align: left; font-weight: bold; padding-left:10px !important"> Subtotal</td>
                        <td>{{ $summary_by_gl->subtotal_pcs_per_gl }}</td>
                        <td>{{ $summary_by_gl->subtotal_dozen_per_gl }}</td>
                    </tr>
                @endforeach
                <tr style="background-color: #bfbfbf;">
                    <td colspan="{{ count($all_size_in_summary) + 7 }}" style="text-align: left; font-weight: bold; padding-left:10px !important"> Total</td>
                    <td>{{ $general_total_pcs }}</td>
                    <td>{{ $general_total_dozen }}</td>
                </tr>
            </tbody>

        </table>

        <br>
        <br>

        <table width="100%" style="font-size: 12px; font-family: Times New Roman, Times, serif; font-weight: bold; position: absolute; bottom: 60px;">
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
</body>
</html>

<style type="text/css">
    * {
        font-family: Calibri, san-serif;
    }

    table.table-bordered > thead > tr > th{
        border-top: 1px solid black;
        border-bottom: 1px solid black;
        border-left: 1px solid black;
        border-right: 1px solid black;
    }

    .table thead th {
        text-align: center;
        vertical-align: middle;
        font-size: 10px;
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