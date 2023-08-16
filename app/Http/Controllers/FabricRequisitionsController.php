<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\FabricRequisition;
use App\Models\LayingPlanningDetail;
use App\Models\LayingPlanning;

use Illuminate\Support\Str;

use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use PDF;

class FabricRequisitionsController extends Controller
{
    public function index()
    {
        return view('page.fabric-requisition.index');
    }

    public function dataFabricRequisition(){
        $query = FabricRequisition::with(['layingPlanningDetail'])
            ->select('fabric_requisitions.id','laying_planning_detail_id','serial_number')->get();
            return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('serial_number', function ($data){
                return $data->serial_number;
            })
            ->addColumn('gl_number', function ($data){
                return $data->layingPlanningDetail->layingPlanning->gl->gl_number;
            })
            ->addColumn('style_no', function ($data){
                return $data->layingPlanningDetail->layingPlanning->style->style;
            })
            ->addColumn('fabric_po', function ($data){
                return $data->layingPlanningDetail->layingPlanning->fabric_po;
            })
            ->addColumn('no_laying_sheet', function ($data){
                return $data->layingPlanningDetail->no_laying_sheet;
            })
            ->addColumn('color', function ($data){
                return $data->layingPlanningDetail->layingPlanning->color->color;
            })
            ->addColumn('table_number', function ($data){
                return $data->layingPlanningDetail->table_number;
            })
            ->addColumn('action', function($data){
                return '
                <a href="'.route('fabric-requisition.print', $data->id).'" class="btn btn-primary btn-sm" target="_blank">Print Nota</a>
                <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="delete_fabricRequisition('.$data->id.')" data-id="'.$data->id.'">Delete</a>
                <a href="'.route('fabric-requisition.show', $data->id).'" class="btn btn-info btn-sm">Detail</a>
                ';
            })
            ->make(true);
    }

    public function createNota($laying_planning_detail_id){
        $layingPlanningDetail = LayingPlanningDetail::find($laying_planning_detail_id);
        $fabricRequisition = FabricRequisition::where('laying_planning_detail_id', $laying_planning_detail_id)->first();

        if($fabricRequisition){
            return back()->with('error', 'Fabric Requisition already exist.');
        }

        $data = [
            'serial_number' => $this->generate_serial_number($layingPlanningDetail),
            'laying_planning_id' => $layingPlanningDetail->layingPlanning->id,
            'laying_planning_detail_id' => $layingPlanningDetail->id,
            'no_laying_sheet' => $layingPlanningDetail->no_laying_sheet,
            'table_number' => $layingPlanningDetail->table_number,
            'gl_number' => $layingPlanningDetail->layingPlanning->gl->gl_number,
            'style' => $layingPlanningDetail->layingPlanning->style->style,
            'color' => $layingPlanningDetail->layingPlanning->color->color,
            'fabric_po' => $layingPlanningDetail->layingPlanning->fabric_po,
            'fabric_type' => $layingPlanningDetail->layingPlanning->fabricType->name,
            'quantity_required' => $layingPlanningDetail->total_length,
            'quantity_issued' => '-',
            'difference' => '-',
        ];

        $data = (object)$data;
        return view('page.fabric-requisition.createNota',compact('data'));
    }

    public function show($id) {
        $getFabricRequisition = FabricRequisition::with(['layingPlanningDetail'])->find($id);
        $layingPlanningDetail = LayingPlanningDetail::find($getFabricRequisition->layingPlanningDetail->id);
        
        $fabric_requisition = [
            'serial_number'=> $layingPlanningDetail->fabricRequisition->serial_number,
            'no_laying_sheet'=> $layingPlanningDetail->no_laying_sheet,
            'table_number' => $layingPlanningDetail->table_number,
            'gl_number' => $layingPlanningDetail->layingPlanning->gl->gl_number,
            'style' => $layingPlanningDetail->layingPlanning->style->style,
            'color' => $layingPlanningDetail->layingPlanning->color->color,
            'fabric_po' => $layingPlanningDetail->layingPlanning->fabric_po,
            'fabric_type' => $layingPlanningDetail->layingPlanning->fabricType->name,
            'quantity_required' => $layingPlanningDetail->total_length . " yards",
            'quantity_issued' => "-",
            'difference' => "-",
        ];

        // $cutting_order_detail = $getCuttingOrder->cuttingOrderRecordDetail;

        // $total_width = 0;
        // $total_weight = 0;
        // $total_layer = 0;
        // foreach( $cutting_order_detail as $key => $detail ){
        //     $total_width += $detail->yardage;
        //     $total_weight += $detail->weight;
        //     $total_layer += $detail->layer;
        // }
        // $fabric_requisition = Arr::add($fabric_requisition, 'total_width', $total_width);
        // $fabric_requisition = Arr::add($fabric_requisition, 'total_weight', $total_weight);
        // $fabric_requisition = Arr::add($fabric_requisition, 'total_layer', $total_layer);

        $fabric_requisition = (object)$fabric_requisition;

        return view('page.fabric-requisition.detail', compact('fabric_requisition'));
    }

    public function store(Request $request)
    {
        $fabricRequisition = FabricRequisition::where('laying_planning_detail_id', $request->laying_planning_detail_id)->first();
        $layingPlanningDetail = LayingPlanningDetail::find($request->laying_planning_detail_id);

        if($fabricRequisition){
            return back()->with('error', 'Fabric Requisition already exist.');
        }
        
        $dataFabricRequisition = [
            'serial_number' => $this->generate_serial_number($layingPlanningDetail),
            'laying_planning_detail_id' => $request->laying_planning_detail_id
        ];
        
        $insertFabricRequisition = FabricRequisition::create($dataFabricRequisition);
        return redirect()->route('fabric-requisition.index')->with('success', 'Fabric Requisition created successfully.');
    }

    public function destroy($id)
    {
        try {
            $fabricRequisition = FabricRequisition::find($id);
            $fabricRequisition->delete();
            $data_return = [
                'status' => 'success',
                'data'=> $fabricRequisition,
                'message'=> 'Data Fabric Requisition berhasil di hapus',
            ];
            return response()->json($data_return, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    public function print_pdf($fabric_requisition_id){

        $fabric_requisition = FabricRequisition::find($fabric_requisition_id);
        $filename = $fabric_requisition->serial_number . '.pdf';

        $data = (object)[
            'serial_number' => $fabric_requisition->serial_number,
            'gl_number' => $fabric_requisition->layingPlanningDetail->layingPlanning->gl->gl_number,
            'style' => $fabric_requisition->layingPlanningDetail->layingPlanning->style->style,
            'fabric_po' => $fabric_requisition->layingPlanningDetail->layingPlanning->fabric_po,
            'no_laying_sheet' => $fabric_requisition->layingPlanningDetail->no_laying_sheet,
            'fabric_type' => $fabric_requisition->layingPlanningDetail->layingPlanning->fabricType->name,   
            'color' => $fabric_requisition->layingPlanningDetail->layingPlanning->color->color,
            'quantity_required' => $fabric_requisition->layingPlanningDetail->total_length . " yards",
            'quantity_issued' => "-",
            'difference' => "-",
            'table_number' => $fabric_requisition->layingPlanningDetail->table_number,   
            'date' => Carbon::now()->format('d-m-Y'),
        ];
        
        // 21.6 cm x 14 cm
        // $customPaper = array(0,0,612.00,380.00);
        $customPaper = array(0,0,612.00,792.00);
        $pdf = PDF::loadview('page.fabric-requisition.print', compact('data'))->setPaper($customPaper, 'portrait');
        return $pdf->stream($filename);
    }
    
    public function print_multiple_fabric_requisition($id)
    {
        $laying_planning = LayingPlanning::find($id);
        $laying_planning_detail = LayingPlanningDetail::where('laying_planning_id', $laying_planning->id)->get();
        $fabric_requisition = FabricRequisition::with(['layingPlanningDetail'])->whereHas('layingPlanningDetail', function($query) use ($id) {
            $query->where('laying_planning_id', $id);
        })->get();
        
        $filename = $laying_planning->gl->gl_number . '.pdf';
        $data = [];
        foreach ($fabric_requisition as $fabric) {
            $data[] = (object)[
                'serial_number' => $fabric->serial_number,
                'gl_number' => $fabric->layingPlanningDetail->layingPlanning->gl->gl_number,
                'style' => $fabric->layingPlanningDetail->layingPlanning->style->style,
                'fabric_po' => $fabric->layingPlanningDetail->layingPlanning->fabric_po,
                'no_laying_sheet' => $fabric->layingPlanningDetail->no_laying_sheet,
                'fabric_type' => $fabric->layingPlanningDetail->layingPlanning->fabricType->name,   
                'color' => $fabric->layingPlanningDetail->layingPlanning->color->color,
                'quantity_required' => $fabric->layingPlanningDetail->total_length . " yards",
                'quantity_issued' => "-",
                'difference' => "-",
                'table_number' => $fabric->layingPlanningDetail->table_number,   
                'date' => Carbon::now()->format('d-m-Y'),
            ];
        }

        $customPaper = array(0,0,612.00,792.00);
        $pdf = PDF::loadview('page.fabric-requisition.print-multiple', compact('data'))->setPaper($customPaper, 'portrait');
        return $pdf->stream($filename);
    }


    public function fabric_requisition_detail(Request $request, $id) {

        // $fabric_requisition_record_detail = CuttingOrderRecordDetail::find($id);
        // $fabric_requisition_record_detail->color = $fabric_requisition_record_detail->color->color;
        // return response()->json([
        //     'status' => 'success',
        //     'data' => $fabric_requisition_record_detail
        // ], 200);
    }

    function generate_serial_number($layingPlanningDetail){
        $gl_number = $layingPlanningDetail->layingPlanning->gl->gl_number;
        $color_code = $layingPlanningDetail->layingPlanning->color->color_code;
        $table_number = Str::padLeft($layingPlanningDetail->table_number, 3, '0');
        
        $serial_number = "FBR-{$gl_number}-{$color_code}-{$table_number}";
        return $serial_number;
    }

    
}
