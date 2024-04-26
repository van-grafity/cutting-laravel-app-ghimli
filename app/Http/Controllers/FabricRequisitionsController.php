<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\FabricRequisition;
use App\Models\LayingPlanningDetail;
use App\Models\LayingPlanning;
use App\Models\FabricIssue;

use Illuminate\Support\Str;

use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use PDF;

use Illuminate\Support\Facades\DB;

class FabricRequisitionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:super_admin|planner|cutter|layer|ticketer|ppc|warehouse', ['only' => ['index', 'create', 'store', 'show', 'destroy']]);
    }

    public function index()
    {
        return view('page.fabric-requisition.index');
    }

    public function dataFabricRequisition(){
            $query = DB::table('fabric_requisitions')
            ->join('laying_planning_details', 'fabric_requisitions.laying_planning_detail_id', '=', 'laying_planning_details.id')
            ->join('laying_plannings', 'laying_planning_details.laying_planning_id', '=', 'laying_plannings.id')
            ->join('gls', 'laying_plannings.gl_id', '=', 'gls.id')
            ->join('styles', 'laying_plannings.style_id', '=', 'styles.id')
            ->join('colors', 'laying_plannings.color_id', '=', 'colors.id')
            ->join('fabric_types', 'laying_plannings.fabric_type_id', '=', 'fabric_types.id')
            ->join('fabric_cons', 'laying_plannings.fabric_cons_id', '=', 'fabric_cons.id')
            ->select('fabric_requisitions.id', 'fabric_requisitions.serial_number', 'fabric_requisitions.is_issue', 'fabric_requisitions.status_print', 'fabric_requisitions.remark', 'laying_planning_details.table_number', 'styles.style', 'colors.color', 'laying_plannings.fabric_po', 'fabric_types.name as fabric_type', 'fabric_cons.name as fabric_cons', 'laying_planning_details.total_length')
            ->orderBy('fabric_requisitions.updated_at', 'desc')->get();
            return Datatables::of($query)
            ->escapeColumns([])
            ->addColumn('serial_number', function ($data){
                return $data->serial_number;
            })
            ->addColumn('fabric_po', function ($data){
                return $data->fabric_po;
            })
            ->addColumn('color', function ($data){
                return $data->color;
            })
            ->addColumn('style', function ($data){
                return $data->style;
            })
            ->addColumn('fabric_type', function ($data){
                return $data->fabric_type;
            })
            ->addColumn('fabric_cons', function ($data){
                return $data->fabric_cons;
            })
            ->addColumn('is_issue', function ($data){
                return $data->is_issue == 1 ? '<span class="badge rounded-pill badge-success" style="padding: 1.2em; text-align: center;">Issued</span>' : '<span class="badge rounded-pill badge-danger" style="padding: 1.2em">Not Issued</span>';
            })
            ->addColumn('action', function($data){
                if(auth()->user()->hasRole('super_admin')){
                    return '
                    <a href="'.route('fabric-requisition.print', $data->id).'" class="btn btn-primary btn-sm" target="_blank"><i class="fas fa-print"></i></a>
                    <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="delete_fabricRequisition('.$data->id.')" data-id="'.$data->id.'"><i class="fas fa-trash"></i></a>
                    <a href="'.route('fabric-requisition.show', $data->id).'" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                    ';
                }else{
                    if($data->status_print == 0){
                        return '
                        <a href="'.route('fabric-requisition.print', $data->id).'" class="btn btn-primary btn-sm" target="_blank"><i class="fas fa-print"></i></a>
                        <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="delete_fabricRequisition('.$data->id.')" data-id="'.$data->id.'"><i class="fas fa-trash"></i></a>
                        <a href="'.route('fabric-requisition.show', $data->id).'" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                        ';
                    }else{
                        return '
                        <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="delete_fabricRequisition('.$data->id.')" data-id="'.$data->id.'"><i class="fas fa-trash"></i></a>
                        <a href="'.route('fabric-requisition.show', $data->id).'" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                        ';
                    }
                }
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
        $fabric_issues = FabricIssue::where('fabric_request_id', $id)->get();
        
        $getFabricRequisition->is_issue = 1;
        $max_min = $getFabricRequisition->layingPlanningDetail->total_length * 0.03;
        if($fabric_issues->sum('yard') <= $getFabricRequisition->layingPlanningDetail->total_length + $max_min && $fabric_issues->sum('yard') >= $getFabricRequisition->layingPlanningDetail->total_length - $max_min && $fabric_issues->sum('yard') != 0){
            $getFabricRequisition->is_issue = 1;
        }else{
            $getFabricRequisition->is_issue = 0;
        }

        $getFabricRequisition->save();
        $fabric_requisition = [
            'id' => $getFabricRequisition->id,
            'serial_number'=> $layingPlanningDetail->fabricRequisition->serial_number,
            'status' => $getFabricRequisition->is_issue,
            'no_laying_sheet' => $layingPlanningDetail->no_laying_sheet,
            'table_number' => $layingPlanningDetail->table_number,
            'gl_number' => $layingPlanningDetail->layingPlanning->gl->gl_number,
            'style' => $layingPlanningDetail->layingPlanning->style->style,
            'color' => $layingPlanningDetail->layingPlanning->color->color,
            'fabric_po' => $layingPlanningDetail->layingPlanning->fabric_po,
            'fabric_type' => $layingPlanningDetail->layingPlanning->fabricType->name,
            'quantity_required' => $layingPlanningDetail->total_length,
            'quantity_issued' => "-",
            'difference' => "-",
            'remark' => $getFabricRequisition->remark,
        ];

        $fabric_requisition = (object)$fabric_requisition;

        return view('page.fabric-requisition.detail', compact('fabric_requisition', 'fabric_issues'));
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

        $fabric_requisition->status_print = 1;
        $fabric_requisition->save();

        return $pdf->stream($filename);
    }

    public function print_multiple_fabric_requisition($id, Request $request)
    {
        $laying_planning_laying_planning_detail_ids = $request->fbr_ids;
        $laying_planning_laying_planning_detail_ids = explode(',', $laying_planning_laying_planning_detail_ids);
        $laying_planning_details = LayingPlanningDetail::whereIn('id', $laying_planning_laying_planning_detail_ids)->get();
        $data = [];
        foreach($laying_planning_details as $laying_planning_detail){
            $fabric_requisition = FabricRequisition::where('laying_planning_detail_id', $laying_planning_detail->id)->first();
            $data[] = [
                'serial_number' => $fabric_requisition->serial_number,
                'gl_number' => $fabric_requisition->layingPlanningDetail->layingPlanning->gl->gl_number,
                'style' => $fabric_requisition->layingPlanningDetail->layingPlanning->style->style,
                'fabric_po' => $fabric_requisition->layingPlanningDetail->layingPlanning->fabric_po,
                'no_laying_sheet' => $this->format_no_laying_sheet($fabric_requisition->layingPlanningDetail->no_laying_sheet),
                'fabric_type' => $fabric_requisition->layingPlanningDetail->layingPlanning->fabricType->name,   
                'color' => $fabric_requisition->layingPlanningDetail->layingPlanning->color->color,
                'quantity_required' => $fabric_requisition->layingPlanningDetail->total_length . " yards",
                'quantity_issued' => "-",
                'difference' => "-",
                'table_number' => $fabric_requisition->layingPlanningDetail->table_number,   
                'date' => Carbon::now()->format('d-m-Y'),
            ];
        }

        $customPaper = array(0,0,612.00,792.00);
        $pdf = PDF::loadview('page.fabric-requisition.print-multiple', compact('data'))->setPaper($customPaper, 'portrait');

        foreach($laying_planning_laying_planning_detail_ids as $laying_planning_laying_planning_detail_id){
            $fabric_requisition = FabricRequisition::where('laying_planning_detail_id', $laying_planning_laying_planning_detail_id)->first();
            $fabric_requisition->status_print = 1;
            $fabric_requisition->save();
        }
        
        return $pdf->stream('fabric-requisition.pdf');
    }

    public function format_no_laying_sheet($no_laying_sheet){
        $no_laying_sheet = preg_replace('/[^0-9\-]/', '', $no_laying_sheet);
        return $no_laying_sheet;
    }
    
    function generate_serial_number($layingPlanningDetail){
        $gl_number = $layingPlanningDetail->layingPlanning->gl->gl_number;
        $color_code = $layingPlanningDetail->layingPlanning->color->color_code;
        $fabric_type = $layingPlanningDetail->layingPlanning->fabricType->name;
        $style = $layingPlanningDetail->layingPlanning->style->id;
        $fabric_type = Str::substr($fabric_type, 0, 4);
        $fabric_type = Str::upper($fabric_type);
        $fabric_type = preg_replace('/[^A-Za-z0-9\-]/', '', $fabric_type);
        $fabric_cons = $layingPlanningDetail->layingPlanning->fabricCons->name;
        $fabric_cons = Str::substr($fabric_cons, 0, 4);
        $fabric_cons = Str::upper($fabric_cons);
        $fabric_cons = preg_replace('/[^A-Za-z0-9\-]/', '', $fabric_cons);
        $table_number = Str::padLeft($layingPlanningDetail->table_number, 3, '0');
        $getDuplicateSN = FabricRequisition::where('laying_planning_detail_id', $layingPlanningDetail->id)->get();

        if ($getDuplicateSN->count() > 0) {
            $duplicate = $getDuplicateSN->count() + 1;
            $duplicate = Str::padLeft($duplicate, 2, '0');
            $serial_number = "FBR-{$gl_number}-{$color_code}{$fabric_type}{$fabric_cons}-S{$style}-{$duplicate}-{$table_number}";
        } else {
            $serial_number = "FBR-{$gl_number}-{$color_code}{$fabric_type}{$fabric_cons}-S{$style}-01-{$table_number}";
        }
        return $serial_number;
    }

    public function get_serial_number(Request $request){
        $date_start = $request->date_start;
        $date_end = $request->date_end;
        $fabric_requisitions = FabricRequisition::whereDate('updated_at', '>=', $date_start)
            ->whereDate('updated_at', '<=', $date_end)
            ->get();
        return response()->json([
            'status' => 'success',
            'data' => $fabric_requisitions
        ], 200);
    }
    
}
