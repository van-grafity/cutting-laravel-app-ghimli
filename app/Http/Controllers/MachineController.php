<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use PDF;
class MachineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function print($serial_number)
    {
        $filename = 'MachineSerialNumber' . '.pdf';
        $data = [
            'serial_number' => $serial_number,
        ];
        $customPaper = array(0,0,180, 300);
        $pdf = PDF::loadview('page.machine.print', compact('data'))->setPaper($customPaper, 'landscape');
        return $pdf->stream($filename);
    }

    public function print_multiple($id, Request $request)
    {
        $data = [];
        $serial_numbers = [];
        $serial_numbers = [
            '1234567890',
            '1234567891',
            '1234567892',
            '1234567893',
            '1234567894',
            '1234567895',
            '1234567896',
            '1234567897',
            '1234567898',
            '1234567899',
            '1234567800',
            '1234567801',
            '1234567802',
            '1234567803',
            '1234567804',
            '1234567805',
            '1234567806',
            '1234567807',
            '1234567808',
            '1234567809',
            '1234567810',
            '1234567811',
            '1234567812',
            '1234567813',
            '1234567814',
            '1234567815',
            '1234567816',
            '1234567817',
            '1234567818',
            '1234567819',
            '1234567820',
            '1234567821',
            '1234567822',
        ];
        $data = [
            'serial_number' => $serial_numbers,
        ];
        foreach ($data['serial_number'] as $key => $value) {
            $data['serial_number'][$key] = [
                'serial_number' => $value,
            ];
        }
        $pdf = PDF::loadview('page.machine.print-multi', compact('data'))->setPaper('a4', 'landscape');
        return $pdf->stream('machine-serial-number.pdf');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
