<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buyer;
use App\Models\Style;
use App\Models\Color;
use App\Models\FabricType;
use App\Models\LayingPlanningDetail;
use App\Models\LayingPlanningDetailSize;
use App\Models\GlCombine;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;


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

    public function gl_combine(Request $request) {
        try {
            $id = $request->id;
            $gl_combine = GlCombine::select('*')
                    ->when($id, function ($query, $id){
                        $query->where('id', $id);
                    })->get();

            $date_return = [
                'status' => 'success',
                'data'=> $gl_combine,
                'message'=> 'Data Gl Combine berhasil diambil',
            ];
            return response()->json($date_return, 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    public function cutting_table(Request $request) {
        try {
            $laying_planning_detail_id = $request->laying_planning_detail_id;
            $laying_planning_detail = LayingPlanningDetail::find($laying_planning_detail_id);

            $laying_planning_detail->size_ratio = $this->print_size_ratio($laying_planning_detail);
            $laying_planning_detail->each_size = $this->print_each_size($laying_planning_detail);
            $laying_planning_detail->total_all_size = $this->sum_all_size($laying_planning_detail);
            
            $date_return = [
                'status' => 'success',
                'data'=> $laying_planning_detail,
                'message'=> 'Data Cutting Table berhasil diambil',
            ];
            return response()->json($date_return, 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    function print_size_ratio($laying_planning_detail){
        $get_size_ratio = LayingPlanningDetailSize::where('laying_planning_detail_id', $laying_planning_detail->id)->get();
        $size_ratio = [];

        foreach( $get_size_ratio as $key => $size ) {
            if($size->ratio_per_size > 0){
                $size_ratio[] = $size->size->size . " = " . $size->ratio_per_size;
            }
        }
        $size_ratio = Arr::join($size_ratio, ' | ');
        return $size_ratio;
    }

    function print_each_size($laying_planning_detail){

        $get_size_qty = LayingPlanningDetailSize::where('laying_planning_detail_id', $laying_planning_detail->id)->get();
        $size_qty = [];

        foreach( $get_size_qty as $key => $size ) {
            if($size->qty_per_size > 0){
                $size_qty[] = $size->size->size . " = " . $size->qty_per_size;
            }
        }
        $size_qty = Arr::join($size_qty, ' | ');
        return $size_qty;
    }

    function sum_all_size($laying_planning_detail){
        $get_size_qty = LayingPlanningDetailSize::where('laying_planning_detail_id', $laying_planning_detail->id)->get();
        $total_qty_all_size = 0;

        foreach( $get_size_qty as $key => $size ) {
            if($size->qty_per_size > 0){
                $total_qty_all_size += $size->qty_per_size;
            }
        }
        return $total_qty_all_size;
    }

}
