<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print PDF</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>
@php
@endphp
<body>
    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Color</th>
                @foreach($data['array_size'] as $size)
                    <th scope="col">{{ $size['size'] }}</th>
                @endforeach
                <th scope="col">Quantity</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['array_size'] as $size)
                @foreach($size['data'] as $item)
                    <tr>
                        <td>{{ $item->no }}</td>
                        <td>{{ $item->color }}</td>
                        @foreach($data['array_size'] as $size)
                            <td>{{ $item->qty }}</td>
                        @endforeach
                        <td>{{ $item->qty }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>