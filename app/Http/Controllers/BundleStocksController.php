<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Gl;
use App\Models\LayingPlanning;
use App\Models\BundleStock;
use Illuminate\Support\Arr;

use Yajra\Datatables\Datatables;

use PDF;
use DB;

class BundleStocksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bundle_stock_list = BundleStock::get();
        $data = [
            'bundle_stock_list' => $bundle_stock_list,
        ];
        return view('page.bundle-stock.index', $data);
    }


    public function dataBundleStock()
    {
        $query = DB::table('bundle_stocks')
            ->join('laying_plannings', 'laying_plannings.id', '=', 'bundle_stocks.laying_planning_id')
            ->join('gls', 'gls.id', '=', 'laying_plannings.gl_id')
            ->join('colors', 'colors.id', '=', 'laying_plannings.color_id')
            ->join('sizes', 'sizes.id', '=', 'bundle_stocks.size_id')
            ->groupBy('bundle_stocks.laying_planning_id')
            ->orderBy('gls.gl_number')
            ->select('laying_plannings.id as laying_planning_id','gls.id as gl_id','gls.gl_number', 'colors.color', DB::raw('SUM(bundle_stocks.current_qty) as total'))
            ->get();

            return Datatables::of($query)
            ->escapeColumns([])
            ->addColumn('action', function($data){
                $action = '<a href="javascript:void(0)" class="btn btn-info btn-sm mb-1" onclick="detail_stock('. $data->laying_planning_id .')" data-toggle="tooltip" data-placement="top" title="Detail" >Detail</a>';
                return $action;
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function detail(Request $request)
    {
        try {
            $laying_planning_id = $request->laying_planning_id;
            $detail_stock = BundleStock::join('laying_plannings','laying_plannings.id','=', 'bundle_stocks.laying_planning_id')
                ->join('gls', 'gls.id', '=', 'laying_plannings.gl_id')
                ->join('colors', 'colors.id', '=', 'laying_plannings.color_id')
                ->join('sizes', 'sizes.id', '=', 'bundle_stocks.size_id')
                ->where('laying_planning_id', $laying_planning_id)
                ->select('gls.gl_number','colors.color','sizes.size','bundle_stocks.current_qty')
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Successfully Get Data Stock',
                'data' => [
                    'detail_stock' => $detail_stock,
                ],
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    public function filter()
    {
        $gls = Gl::select('id', 'gl_number')->get();
        return view('page.bundle-stock.filter', compact('gls'));
    }

    public function print(Request $request)
    {
        $gl_id = $request->gl_id;
        $gl = Gl::find($gl_id);
        $filename = 'Cut Piece Stock #GL'.$gl->gl_number.'.pdf';
        $gl_number = 'GL-' . $gl->gl_number;

        $stock_item_list = $this->getStocItemkList($gl_id); // ## this is laying plannings
        
        $laying_planning_id_list = array_column($stock_item_list, 'laying_planning_id');
        $size_list = $this->getSizeList($laying_planning_id_list);
        $total_size = count($size_list);
        
        // ## get qty per stock item
        foreach ($stock_item_list as $key => $item) {
            $qty_per_size = $this->getQtyPerSize($item['laying_planning_id'], $size_list);
            $stock_item_list[$key]['qty_per_size'] = $qty_per_size;
            
            $total_qty_all_size = array_reduce($qty_per_size, function ($total_qty, $size) {
                return $total_qty + $size['qty'];
            }, 0);
            $stock_item_list[$key]['total_qty_all_size'] = $total_qty_all_size;
        }

        $data = [
            'gl_number' => $gl_number,
            'filename' => $filename,
            'stock_item_list' => $stock_item_list,
            'size_list' => $size_list,
            'total_size' => $total_size,
        ];
        // dd($data);
        

        // return view('page.bundle-stock.report', $data);
        
        $pdf = PDF::loadView('page.bundle-stock.report', $data);
        return $pdf->stream($filename);
    }

    private function getStocItemkList($gl_id = null) : array
    {
        if(!$gl_id) { return []; }
        return LayingPlanning::where('gl_id', $gl_id)
            ->join('colors','colors.id','=','laying_plannings.color_id')
            ->join('gls','gls.id','=','laying_plannings.gl_id')
            ->select('laying_plannings.id as laying_planning_id','laying_plannings.serial_number as laying_planning_number','gls.gl_number','colors.color')
            ->get()->toArray();
    }

    private function getSizeList(Array $laying_planning_id_list) : array
    {
        return LayingPlanning::whereIn('laying_plannings.id',$laying_planning_id_list)
            ->join('laying_planning_details','laying_planning_details.laying_planning_id','=','laying_plannings.id')    
            ->join('laying_planning_detail_sizes','laying_planning_detail_sizes.laying_planning_detail_id','=','laying_planning_details.id')
            ->join('sizes','sizes.id','=','laying_planning_detail_sizes.size_id')
            ->groupBy('laying_planning_detail_sizes.size_id')
            ->select('sizes.id','sizes.size')
            ->get()->toArray();
    }

    private function getQtyPerSize($laying_planning_id, $size_list)
    {
        $getBundleStock = LayingPlanning::where('laying_plannings.id', $laying_planning_id)
            ->join('bundle_stocks','bundle_stocks.laying_planning_id','=','laying_plannings.id')    
            ->select('bundle_stocks.*')
            ->get()->toArray();
        
        
        if(!$getBundleStock){
            // ## Jika tidak ada ticket dari laying planning ini yang tersimpan di rack, berarti semua size stocknya 0
            foreach ($size_list as $key => $size) {
                $size_list[$key]['qty'] = 0; 
            }
        } else {
            /*
            * Jika ada ticket dari laying planning ini yang tersimpan di rack.
            * Melakukan pengecekkan untuk semua size list. lalu ambil current qty dari size yang berkaitan. => using array_filter 
            * jika ada size tidak di temukan di rack. Maka qty = 0
             */
            foreach ($size_list as $key => $size) {
                $size_id = $size['id'];
                $filter_result = array_filter($getBundleStock, function ($bundle) use ($size_id) {
                    return $bundle['size_id'] === $size_id;
                });
                
                if($filter_result){
                    $size_list[$key]['qty'] = reset($filter_result)['current_qty']; 
                } else {
                    $size_list[$key]['qty'] = 0; 
                }
            }
        }
        return $size_list;
    }
}
