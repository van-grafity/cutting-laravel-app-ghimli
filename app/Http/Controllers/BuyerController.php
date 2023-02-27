<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BuyerController extends Controller
{
    
    public function index()
    {
        return view('page.buyer.index');
    }

    public function store(Request $request)
    {
        dd($request);
        // $request->validate([
        //     'name' => 'required',
        //     'style_number' => 'required',
        //     'table_number' => 'required',
        //     'next_bundling' => 'required',
        //     'color' => 'required',
        //     'size' => 'required',
        // ]);

        // $cutting = new Cutting;
        // $cutting->job_number = $request->job_number;
        // $cutting->style_number = $request->style_number;
        // $cutting->table_number = $request->table_number;
        // $cutting->next_bundling = $request->next_bundling;
        // $cutting->color = $request->color;
        // $cutting->size = $request->size;
        // $cutting->save();

        // return redirect('/cutting')->with('status', 'Data Cutting Berhasil Ditambahkan!');
        return redirect('/buyer');

    }
}
