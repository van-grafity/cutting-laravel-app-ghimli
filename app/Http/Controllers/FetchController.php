<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buyer;
use App\Models\Style;
use App\Models\Color;
use App\Models\FabricType;

class FetchController extends Controller
{
    
    public function index()
    {
        try {
            $fetch_list = [
                'buyer' => [
                    'route.name' => "fetch.buyer",
                    'options' => ['id', 'gl_id']
                ],
                'style' => [
                    'route.name' => "fetch.style",
                    'options' => ['id', 'gl_id']
                ],
                'color' => [
                    'route.name' => "fetch.color",
                    'options' => ['id']
                ],
                'fabric_type' => [
                    'route.name' => "fetch.fabric-type",
                    'options' => ['id']
                ],
            ];
            $date_return = [
                'status' => 'success',
                'data'=> $fetch_list,
                'message'=> 'Fetch List',
            ];
            return response()->json($date_return, 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    public function buyer(Request $request) {
        try {
            $id = $request->id;
            $gl_id = $request->gl_id;
            $buyer = Buyer::select('buyers.id', 'buyers.name')
                    ->join('gls','gls.buyer_id','=','buyers.id')
                    ->when($id, function ($query, $id){
                        $query->where('buyers.id', $id);
                    })
                    ->when($gl_id, function ($query, $gl_id){
                        $query->where('gls.id', $gl_id);
                    })->get();

            $date_return = [
                'status' => 'success',
                'data'=> $buyer,
                'message'=> 'Data Buyer berhasil diambil',
            ];
            return response()->json($date_return, 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }
    
    public function style(Request $request) {
        try {
            $id = $request->id;
            $gl_id = $request->gl_id;
            $style = Style::select('styles.id', 'styles.style', 'styles.description')
                    ->join('gls','gls.id','=','styles.gl_id')
                    ->when($id, function ($query, $id){
                        $query->where('styles.id', $id);
                    })
                    ->when($gl_id, function ($query, $gl_id){
                        $query->where('gls.id', $gl_id);
                    })
                    ->get();

            $date_return = [
                'status' => 'success',
                'data'=> $style,
                'message'=> 'Data Style berhasil diambil',
            ];
            return response()->json($date_return, 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    public function color(Request $request) {
        try {
            $id = $request->id;
            $color = Style::select('*')
                    ->when($id, function ($query, $id){
                        $query->where('id', $id);
                    })->get();

            $date_return = [
                'status' => 'success',
                'data'=> $color,
                'message'=> 'Data Color berhasil diambil',
            ];
            return response()->json($date_return, 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }
    
    public function fabric_type(Request $request) {
        try {
            $id = $request->id;
            $fabric_type = FabricType::select('*')
                    ->when($id, function ($query, $id){
                        $query->where('id', $id);
                    })->get();

            $date_return = [
                'status' => 'success',
                'data'=> $fabric_type,
                'message'=> 'Data Fabric Type berhasil diambil',
            ];
            return response()->json($date_return, 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }
}
