<?php

namespace App\Http\Controllers;

use App\Models\Pallet;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PDF;

class PalletsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pallets = Pallet::all();
        return view('page.pallets.index', compact('pallets'));
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
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Pallet $pallet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pallet $pallet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pallet $pallet)
    {
        //
    }

    public function print()
    {
        // $pallets = Pallet::where('serial_number', 'like', 'PLT-A%')->take(10)->get();
        $pallets = Pallet::where('serial_number', 'like', 'PLT-A%')->skip(10)->take(90)->get();
        $qrCodes = [];
        foreach ($pallets as $pallet) {
            $qrCodes[] = QrCode::size(100)->generate($pallet->serial_number);
        }
        $customPaper = array(0, 0, 360, 360);
        $pdf = PDF::loadview('page.pallets.print', compact('pallets', 'qrCodes'))->setPaper($customPaper, 'landscape');
        $filename = 'pallets-' . date('YmdHis') . '.pdf';
        return $pdf->stream($filename);
    }

    public function printt()
    {
        // $pallets = Pallet::where('serial_number', 'like', 'PLT-B%')->take(1)->get();
        $pallets = Pallet::where('serial_number', 'like', 'PLT-A%')->skip(100)->take(150)->get();
        $qrCodes = [];
        foreach ($pallets as $pallet) {
            $qrCodes[] = QrCode::size(100)->generate($pallet->serial_number);
        }
        $customPaper = array(0, 0, 360, 360);
        $pdf = PDF::loadview('page.pallets.print', compact('pallets', 'qrCodes'))->setPaper($customPaper, 'landscape');
        $filename = 'pallets-' . date('YmdHis') . '.pdf';
        return $pdf->stream($filename);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pallet $pallet)
    {
        //
    }
}
