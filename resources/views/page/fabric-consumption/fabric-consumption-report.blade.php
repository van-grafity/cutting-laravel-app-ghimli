<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fabric Consumption Report</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous"> -->

    <style type="text/css">
        .table-tracking thead th {
            border: 1px black solid;
            text-align: center;
            vertical-align: middle;
            font-size: 10px;
            padding-top: 2 !important;
            padding-bottom: 2 !important;
        }

        .table-tracking tbody td {
            border: 1px black solid;
            vertical-align: middle;
            font-weight: bold;
            font-size: 10px;
            padding-top: 2 !important;
            padding-bottom: 2 !important;
            padding-left: 5 !important;
            padding-right: 5 !important;
        }
	</style>
</head>
<body>
    <div>
        <div class="row">
            <div class="col-lg-12 text-center mb-3">
                <h4  style="text-align: center; font-weight: bold;">Fabric Consumption Report</h4>
            </div>
        </div>
        <table width="100%" class="table-tracking">
            <thead>
                <tr>
                    <th width="2%">No.</th>
                    <th width="10%">GL No.</th>
                    <th width="15%">Color</th>
                    <th width="6.5%">Consumption Plan</th>
                    <th width="6.5%">Actual Consumpt</th>
                    <th width="6.5%">Balance</th>
                    <th width="6.5%">Completion</th>
                    <th width="6.5%">Replacement</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach($data as $item)
                <tr>
                    <td style="text-align: center;">{{$i}}</td>
                    <td style="text-align: center;">{{ $item['gl_number'] }}</td>
                    <td style="text-align: center;">{{ $item['color'] }}</td>
                    <td style="text-align: center;">{{ $item['planning_consumption'] }}</td>
                    <td style="text-align: center;">{{ $item['actual_consumption'] }}</td>
                    <td style="text-align: center;">{{ $item['balance'] }}</td>
                    <td style="text-align: center;">{{ $item['completion'] }}</td>
                    <td style="text-align: center;">{{ $item['replacement'] }}</td>
                </tr>
                @php $i++; @endphp
            @endforeach
            </tbody>

        </table>
    </div>
</body>
</html>
