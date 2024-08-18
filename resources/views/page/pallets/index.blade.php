<!-- controller -->
<!-- public function index()
    {
        $pallets = Pallet::all();
        return view('pallets.index', compact('pallets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // for ($i = 1; $i <= 1750; $i++) {
        //     $pallet = Pallet::create([
        //         'serial_number' => 'PLT-A' . str_pad($i, 4, '0', STR_PAD_LEFT),
        //         'description' => 'Pallet A'
        //     ]);
        // }
        // for ($i = 1; $i <= 850; $i++) {
        //     $pallet = Pallet::create([
        //         'serial_number' => 'PLT-B' . str_pad($i, 4, '0', STR_PAD_LEFT),
        //         'description' => 'Pallet B'
        //     ]);
        // }

        // select count(*) from pallets where serial_number like 'PLT-A%';
    } -->

@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <a href="{{ route('pallets.print') }}" class="btn btn-primary mb-3">Print</a>
                <a href="{{ route('pallets.printt') }}" class="btn btn-primary mb-3">Printt</a>
                @if (count($pallets) > 0)
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Serial Number</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pallets as $pallet)
                                <tr>
                                    <td>{{ $pallet->serial_number }}</td>
                                    <td>{{ $pallet->description }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>No pallets found.</p>
                @endif
            </div>
        </div>
    </div>
@endsection