<?php

namespace App\Http\Controllers;
use App\Models\Cutting;

use Illuminate\Http\Request;

class CuttingQrCodeController extends Controller
{
    public function index()
    {
      return view('page.cutting.qrcode.index');
    }

    public function show($id)
    {
      $data = Cutting::find($id);
      return view('page.cutting.qrcode.show', compact('data'));
    }
}