<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\Color;

class CuttingTicketsController extends Controller
{
    public function index()
    {
        // $colors = Color::all();
        return view('page.cutting-ticket.index');
    }

    // public function create()
    // {
    //     return view('page.cutting-ticket.index');
    // }

    public function createTicket() {
        return view('page.cutting-ticket.add');
    }

    public function show($id) {
        // return view('page.cutting-ticket.detail');
    }

}
