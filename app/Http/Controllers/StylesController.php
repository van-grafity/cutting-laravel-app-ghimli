<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Style;
use Illuminate\Support\Facades\DB;

class StylesController extends Controller
{
    public function index()
    {
        // $colors = Style::all();
        // return view('page.color.index', compact('colors'));
    }

    public function show($id){
        $data = Style::find($id);
        try {
            $data = Style::find($id);
            return response()->json($data, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    public function getStyle(Request $request) {

        if($request->id) {
            $data = Style::find($request->id);
            return response()->json($data, 200);
        }

        if($request->gl_id) {
            $data = Style::where('gl_id', $request->gl_id)->get();
            return response()->json($data, 200);
        }
        $data = Style::all();
        return response()->json($data, 200);
    }

}
