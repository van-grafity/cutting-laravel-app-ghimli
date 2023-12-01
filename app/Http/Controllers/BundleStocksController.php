<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Gl;
use App\Models\LayingPlanning;
use Illuminate\Support\Arr;

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
        return view('page.index');
    }

    public function report()
    {
        $gl_id = '376';
        $gl = Gl::find($gl_id);
        $filename = 'Cut Piece Stock #GL'.$gl->gl_number.'.pdf';

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
                    $size_list[$key]['qty'] = $filter_result[0]['current_qty']; 
                } else {
                    $size_list[$key]['qty'] = 0; 
                }
            }
        }
        return $size_list;
    }
}
