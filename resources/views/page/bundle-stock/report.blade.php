<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $filename }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style type="text/css">
        @page {
            margin-top: 1cm;
            margin-left: 1.5cm;
            margin-right: 2cm;
            margin-bottom: 1cm;
        }

        .table td, .table th {
            padding: 0.50rem;
        }

        .table thead th {
            border: 1px black solid;
            text-align: center;
            vertical-align: middle;
            font-size: 10px;
        }
        
        .table tbody td {
            text-align: center;
            border: 1px black solid;
            vertical-align: middle;
            font-weight: bold;
            font-size: 10px;
            padding-top: .25rem;
            padding-bottom: .25rem;
        }

        .report-title {
            font-weight: bold;
            font-size: 24px;
        }
        .report-subtitle {
            font-size: 18px;
        }

        .size-column {
            width: 20px;
        }
        
	</style>
</head>
<body>
    <div>
        <div class="row">
            <div class="col-12">
                <div class="content-title text-center">
                    <div class="report-title">
                        Cut Piece Stock Report
                        <br>
                        <div class="report-subtitle">{{ $gl_number }}</div>
                    </div>
                </div>

                </br>

                <table class="table" id="cut_piece_stock_table">
                    <thead class="">
                        <tr>
                            <th rowspan="2" style="width:10px">No.</th>
                            <th rowspan="2" style="width:50px">GL No.</th>
                            <th rowspan="2">Color</th>
                            <th rowspan="1" colspan="{{ $total_size }}">Size</th>
                            <th rowspan="2" class="size-column">Total</th>
                        </tr>
                        <tr>
                            @foreach ($size_list as $key => $size)
                                <th class="size-column">{{ $size['size'] }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @if(!$stock_item_list)
                            <tr>
                                <td colspan="5"> No Data</td>
                            </tr>
                        @endif
                        @foreach($stock_item_list as $key => $stock_item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $stock_item['gl_number'] }}</td>
                                <td class="text-left">{{ $stock_item['color'] }}</td>
                                @foreach ($stock_item['qty_per_size'] as $key_size => $qty_size)
                                    <td>{{ $qty_size['qty'] }}</td>
                                @endforeach
                                <td>{{ $stock_item['total_qty_all_size']; }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>