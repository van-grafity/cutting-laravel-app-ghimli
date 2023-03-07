<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\Color;

class CuttingOrdersController extends Controller
{
    public function index()
    {
        // $colors = Color::all();
        return view('page.cutting-order.index');
    }

    // public function create()
    // {
    //     return view('page.cutting-order.index');
    // }

    public function createNota($id) {
        return view('page.cutting-order.createNota');
    }

    public function show($id) {
        return view('page.cutting-order.detail');
    }

}
