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

		  table > thead > tr > th{
        }

        .table thead th {
            border: 1px black solid;
            text-align: center;
            vertical-align: middle;
            font-size: 10px;
            padding-top: 2 !important;
            padding-bottom: 2 !important;
        }
        
        .table tbody td {
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
            <div class="col-12">
                <div class="content-title text-center">
                    <h3 class="">Cut Piece Stock Report</h3>
                </div>

                </br>

                <table class="table" id="cut_piece_stock_table">
                    <thead class="">
                        <tr>
                            <th scope="col" rowspan="2">No.</th>
                            <th scope="col" rowspan="2">GL No.</th>
                            <th scope="col" rowspan="2">Color</th>
                            <th scope="col" colspan="{{ $total_size }}">Size</th>
                            <th scope="col" rowspan="2">Total</th>
                        </tr>
                        <tr>
                            @foreach ($size_list as $key => $size)
                                <th scope="col">{{ $size['size'] }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stock_item_list as $key => $stock_item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $stock_item['gl_number'] }}</td>
                                <td>{{ $stock_item['color'] }}</td>
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