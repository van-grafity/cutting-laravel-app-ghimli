<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CuttingTicket;
use App\Models\CuttingOrderRecord;
use App\Models\LayingPlanningDetail;
use App\Models\LayingPlanningDetailSize;
use App\Models\GlCombine;
use App\Models\LayingPlanningSizeGlCombine;
use App\Models\LayingPlanningSize;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;

use Yajra\Datatables\Datatables;
use PDF;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CuttingTicketsController extends Controller
{
    public function index()
    {
        return view('page.cutting-ticket.index');
    }

    public function ticketListByCOR($id)
    {
        $get_cutting_tickets = CuttingTicket::with(['cuttingOrderRecord.layingPlanningDetail.layingPlanning.color', 'size'])
            ->select('cutting_tickets.id','cutting_order_record_id','size_id','layer','ticket_number')
            ->whereHas('cuttingOrderRecord', function($q) use ($id){
                $q->where('id', $id);
            })
            ->get();

        $tickets = [];
        foreach ($get_cutting_tickets as $key => $ticket) {
            $tickets[] = (object)[
                'id' => $ticket->id
            ];
        }

        $serial_number = $get_cutting_tickets[0]->cuttingOrderRecord->serial_number;

        return view('page.cutting-ticket.ticket-list-by-cor',compact('tickets', 'serial_number', 'id'));
    }

    public function dataCuttingTicket(){
        $query = DB::table('cutting_order_records')
            ->join('laying_planning_details', 'cutting_order_records.laying_planning_detail_id', '=', 'laying_planning_details.id')
            ->join('laying_plannings', 'laying_planning_details.laying_planning_id', '=', 'laying_plannings.id')
            ->join('gls', 'laying_plannings.gl_id', '=', 'gls.id')
            ->join('styles', 'laying_plannings.style_id', '=', 'styles.id')
            ->join('colors', 'laying_plannings.color_id', '=', 'colors.id')
            ->join('fabric_types', 'laying_plannings.fabric_type_id', '=', 'fabric_types.id')
            ->join('fabric_cons', 'laying_plannings.fabric_cons_id', '=', 'fabric_cons.id')
            ->whereRaw('cutting_order_records.id IN (SELECT cutting_order_record_id FROM cutting_tickets)')
            ->select(
                'cutting_order_records.id',
                'cutting_order_records.serial_number',
                'styles.style',
                'colors.color',
                'fabric_types.name as fabric_type',
                'fabric_cons.name as fabric_cons',
                'cutting_order_records.updated_at'
            )
            ->orderBy('cutting_order_records.updated_at', 'desc');

        return Datatables::of($query)
            ->escapeColumns([])
            ->addColumn('ticket_number', function($data){
                return $data->serial_number == null ? '-' : $data->serial_number;
            })
            ->addColumn('color', function($data){
                return $data->color;
            })
            ->addColumn('style', function($data){
                return $data->style;
            })
            ->addColumn('fabric_type', function($data){
                return $data->fabric_type;
            })
            ->addColumn('fabric_cons', function($data){
                return $data->fabric_cons;
            })
            ->addColumn('action', function($data){
                return '
                <a href="'.route('cutting-ticket.print-multiple', $data->id).'"  target="_blank"class="btn btn-primary btn-sm btn-print-ticket"><i class="fa fa-print"></i></a>
                <a href="'.route('cutting-ticket.detail', $data->id).'" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>
                <a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="delete_ticket('. $data->id .')"><i class="fa fa-trash"></i></a>
                <a href="javascript:void(0)" class="btn btn-warning btn-sm" onclick="refresh_ticket('. $data->id .')"><i class="fa fa-sync"></i></a>
                ';
            })
            ->make(true);
    }

    public function dataCuttingTicketByCOR($id){
        $query = CuttingTicket::with([])
            ->select('cutting_tickets.id','cutting_order_record_id','size_id','layer','ticket_number')
            ->whereHas('cuttingOrderRecord', function($q) use ($id){
                $q->where('id', $id);
            })
            ->get();
            return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('ticket_number', function($data){
                return $this->generate_ticket_number($data->id);
            })
            ->addColumn('no_laying_sheet', function($data){
                return $data->cuttingOrderRecord->layingPlanningDetail->no_laying_sheet;
            })
            ->addColumn('table_number', function($data){
                return $data->cuttingOrderRecord->layingPlanningDetail->table_number;
            })
            ->addColumn('color', function($data){
                return $data->cuttingOrderRecord->layingPlanningDetail->layingPlanning->color->color;
            })
            ->addColumn('size', function($data){
                return $data->size->size;
            })
            ->addColumn('layer', function($data){
                return $data->layer;
            })
            ->addColumn('action', function($data){
                return '
                <a href="'.route('cutting-ticket.print', $data->id).'" target="_blank" class="btn btn-primary btn-sm btn-print-ticket">Print</a>
                <a href="javascript:void(0)" class="btn btn-info btn-sm" onclick="show_detail_ticket('. $data->id .')">Detail</a>
                ';
            })
            ->make(true);
    }

    public function create() {
        $data = CuttingOrderRecord::with(['statusLayer', 'statusCut', 'layingPlanningDetail', 'cuttingOrderRecordDetail'])
            ->where('id_status_cut', 2)
            ->whereNotIn('id', function($query) {
                $query->select('cutting_order_record_id')->from('cutting_tickets');
            })
            ->select('cutting_order_records.id','laying_planning_detail_id','serial_number')->get();
        $cutting_order_records = [];
        foreach ($data as $key => $value) {
            $sum_layer = 0;
            foreach ($value->cuttingOrderRecordDetail as $detail) {
                $sum_layer += $detail->layer;
            }
            if ($sum_layer == $value->layingPlanningDetail->layer_qty) {
                $cutting_order_records[] = $value;
            } else if ($sum_layer > $value->layingPlanningDetail->layer_qty) {
                $cutting_order_records[] = $value;
            }
        }
        return view('page.cutting-ticket.add', compact('cutting_order_records'));
    }

    public function show($id){
        $data = CuttingTicket::find($id);
        try {
            $ticket = CuttingTicket::find($id);
            $layingPlanningDetail = $ticket->cuttingOrderRecord->layingPlanningDetail;
            $data = [
                'id' => $ticket->id,
                'ticket_number' => $this->generate_ticket_number($ticket->id),
                'no_laying_sheet' => $layingPlanningDetail->no_laying_sheet,
                'table_number' => Str::padLeft($layingPlanningDetail->table_number, 3, '0'),
                'gl_number' => $layingPlanningDetail->layingPlanning->gl->gl_number,
                'buyer' => $layingPlanningDetail->layingPlanning->buyer->name,
                'style' => $layingPlanningDetail->layingPlanning->style->style,
                'color' => $layingPlanningDetail->layingPlanning->color->color,
                'size' => $ticket->size->size,
                'layer' => $ticket->layer,
                'fabric_roll' => $ticket->cuttingOrderRecordDetail->fabric_roll,
                'fabric_po' => $layingPlanningDetail->layingPlanning->fabric_po,
                'fabric_type' => $layingPlanningDetail->layingPlanning->fabricType->name,
                'fabric_cons' => $layingPlanningDetail->layingPlanning->fabricCons->name,
            ];
            return response()->json($data, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    public function get_cutting_order($cutting_order_record_id)
    {
        try {
            $cuttingOrderRecord = CuttingOrderRecord::find($cutting_order_record_id);
            $layingPlanningDetail = $cuttingOrderRecord->layingPlanningDetail;

            $data = [
                'laying_planning_detail_id' => $layingPlanningDetail->id,
                'gl_number' => $layingPlanningDetail->layingPlanning->gl->gl_number,
                'table_number' => $layingPlanningDetail->table_number,
                'style' => $layingPlanningDetail->layingPlanning->style->style,
                'color' => $layingPlanningDetail->layingPlanning->color->color,
                'buyer' => $layingPlanningDetail->layingPlanning->buyer->name,
            ];

            $size_ratio = $this->print_size_ratio($layingPlanningDetail);
            $data = Arr::add($data, 'size_ratio', $size_ratio);
            $data = (object)$data;

            $date_return = [
                'status' => 'success',
                'data'=> $data,
                'message'=> 'Data Laying Sheet berhasil di hapus',
            ];
            return response()->json($date_return, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    public function generate_ticket(Request $request)
    {
        try {
            $next_ticket_number = 1;
            $cuttingOrderRecord = CuttingOrderRecord::find($request->cutting_order_id);
            $layingPlanningDetail = LayingPlanningDetail::find($cuttingOrderRecord->laying_planning_detail_id);
            $planning_size_list = $layingPlanningDetail->layingPlanningDetailSize;
            $cutting_order_details = $cuttingOrderRecord->cuttingOrderRecordDetail;
            foreach ($planning_size_list as $planning_size) {
                $ratio_per_size = $planning_size->ratio_per_size;
                for ($i=0; $i < $ratio_per_size; $i++) {
                    foreach($cutting_order_details as $cutting_order_detail) {
                        $data_ticket = [
                            'ticket_number' => $next_ticket_number,
                            'size_id'=> $planning_size->size_id,
                            'layer'=> $cutting_order_detail->layer,
                            'cutting_order_record_id'=> $cutting_order_detail->cuttingOrderRecord->id,
                            'cutting_order_record_detail_id'=> $cutting_order_detail->id,
                            'table_number'=> $cutting_order_detail->cuttingOrderRecord->layingPlanningDetail->table_number,
                            'fabric_roll'=> $cutting_order_detail->fabric_roll,
                        ];
                        $insertCuttingTicket = CuttingTicket::create($data_ticket);
                        $insertCuttingTicket->serial_number = $this->generate_ticket_number($insertCuttingTicket->id);
                        $insertCuttingTicket->save();
                        $next_ticket_number++;
                    }
                }
            }
            return redirect()->route('cutting-ticket.index')
                ->with('success', 'Data Cutting Ticket berhasil di generate');
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }

    }

    public function print_report_pdf($id) {
        $cuttingOrderRecord = CuttingOrderRecord::with(['layingPlanningDetail', 'layingPlanningDetail.layingPlanningDetailSize.size', 'cuttingOrderRecordDetail', 'cuttingOrderRecordDetail.color', 'cuttingTicket', 'cuttingTicket.cuttingOrderRecordDetail', 'cuttingTicket.cuttingOrderRecordDetail.color', 'cuttingTicket.size'])
        ->whereHas('cuttingTicket', function($q) use ($id) {
            $q->where('cutting_order_record_id', $id);
        })->first();
        $cuttingTickets = $cuttingOrderRecord->cuttingTicket;
        $cuttingOrderRecordDetail = $cuttingOrderRecord->cuttingOrderRecordDetail;
        $layingPlanningDetailSize = $cuttingOrderRecord->layingPlanningDetail->layingPlanningDetailSize;
        $layingPlanningDetailSize = $layingPlanningDetailSize->toArray();
        $layingPlanningDetailSize = Arr::sort($layingPlanningDetailSize, function ($value) {
            return $value['size_id'];
        });
        $layingPlanningDetailSize = Arr::pluck($layingPlanningDetailSize, 'ratio_per_size', 'size_id');
        $layingPlanningDetailSize = (object)$layingPlanningDetailSize;
        $layingPlanningDetail = $cuttingOrderRecord->layingPlanningDetail;
        $layingPlanning = $layingPlanningDetail->layingPlanning;
        $gl = $layingPlanning->gl;
        $style = $layingPlanning->style;
        $color = $layingPlanning->color;
        $buyer = $gl->buyer;
        $data = [
            'cutting_order_record' => $cuttingOrderRecord,
            'cutting_tickets' => $cuttingTickets,
            'cutting_order_record_detail' => $cuttingOrderRecordDetail,
            'laying_planning_detail_size' => $layingPlanningDetailSize,
            'color' => $color,
        ];
        $customPaper = array(0, 0, 794.00, 612.00);
        $pdf = PDF::loadview('page.cutting-ticket.report', compact('data'))->setPaper($customPaper, 'portrait');
        return $pdf->stream('PackingList' . '.pdf');
    }

    public function print_ticket(Request $request, $ticket_id) {

        $ticket = CuttingTicket::find($ticket_id);
        $filename = $ticket->serial_number . '.pdf';

        $layingPlanningDetail = $ticket->cuttingOrderRecord->layingPlanningDetail;


        $data = (object)[
            'serial_number' => $ticket->serial_number,
            'buyer' => $layingPlanningDetail->layingPlanning->gl->buyer->name,
            'size' => $ticket->size->size,
            'color' => $layingPlanningDetail->layingPlanning->color->color,
            'ticket_number' => Str::padLeft($ticket->ticket_number, 3, '0'),
            'layer' => $ticket->layer,
        ];
        // return view('page.cutting-ticket.print',compact('data'));
        $customPaper = array(0,0,210.24, 302.00);
        $pdf = PDF::loadview('page.cutting-ticket.print', compact('data'))->setPaper($customPaper, 'landscape');
        return $pdf->stream($filename);
    }

    public function print_multiple($id) {
        $cutting_order_record = CuttingOrderRecord::where('id', $id)->first();
        $cutting_tickets = CuttingTicket::where('cutting_order_record_id', $cutting_order_record->id)->get();
        $filename = $cutting_tickets[0]->cuttingOrderRecord->serial_number . '.pdf';

        $data = [];
        foreach ($cutting_tickets as $ticket) {
            $layingPlanningDetail = $ticket->cuttingOrderRecord->layingPlanningDetail;
            $data[] = (object)[
                'serial_number' => $ticket->serial_number,
                'gl_number' => $layingPlanningDetail->layingPlanning->gl->gl_number,
                'buyer' => $layingPlanningDetail->layingPlanning->gl->buyer->name,
                'size' => $ticket->size->size,
                'color' => $layingPlanningDetail->layingPlanning->color->color,
                'ticket_number' => Str::padLeft($ticket->ticket_number, 3, '0'),
                'style' => $layingPlanningDetail->layingPlanning->style->style,
                'layer' => $ticket->layer,
            ];
        }

        // 10.1 cm x 6.3 cm
        $customPaper = array(0,0,180.00, 298.00);
        $pdf = PDF::loadview('page.cutting-ticket.print-all', compact('data'))->setPaper($customPaper, 'landscape');
        return $pdf->stream($filename);
    }

    function generate_ticket_number($ticket_id) {
        $ticket = CuttingTicket::find($ticket_id);

        $gl_number = $ticket->cuttingOrderRecord->layingPlanningDetail->layingPlanning->gl->gl_number;

        $color_code = $ticket->cuttingOrderRecord->layingPlanningDetail->layingPlanning->color->color_code;

        $size = $ticket->size->size;

        $gl_combine = '';
        $layingPlanningSizeGlCombine = LayingPlanningSizeGlCombine::with('layingPlanningSize.size', 'glCombine')
            ->whereHas('layingPlanningSize', function($q) use ($ticket) {
                $q->where('laying_planning_id', $ticket->cuttingOrderRecord->layingPlanningDetail->layingPlanning->id);
                $q->where('size_id', $ticket->size_id);
            })->get();

        if ($layingPlanningSizeGlCombine->isEmpty()) {
            $gl_combine = $gl_number;
        } else {
            $layingPlanningSize = LayingPlanningSize::where('laying_planning_id', $ticket->cuttingOrderRecord->layingPlanningDetail->layingPlanning->id)->where('size_id', $ticket->size_id)->first();
            $layingPlanningSizeGlCombine = $layingPlanningSizeGlCombine->where('id_laying_planning_size', $layingPlanningSize->id)->first();
            $gl_combine = $layingPlanningSizeGlCombine->glCombine->name;
            $gl_combine = $gl_number . $gl_combine;
        }

        $table_number = $ticket->cuttingOrderRecord->layingPlanningDetail->table_number;
        $table_number = Str::padLeft($table_number, 3, '0');

        $ticket_number = Str::padLeft($ticket->ticket_number, 3, '0');

        return "CT-{$gl_combine}-{$size}-{$color_code}-{$table_number}-{$ticket_number}";
    }

    function print_size_ratio($layingPlanningDetail) {
        $get_size_ratio = LayingPlanningDetailSize::where('laying_planning_detail_id', $layingPlanningDetail->id)->get();
        $size_ratio = [];

        foreach( $get_size_ratio as $key => $size ) {
            if($size->ratio_per_size > 0){
                $size_ratio[] = $size->size->size . " = " . $size->ratio_per_size;
            }
        }
        $size_ratio = Arr::join($size_ratio, ' | ');
        return $size_ratio;
    }

    public function delete_ticket($id) {
        try {
            $cuttingOrderRecord = CuttingOrderRecord::find($id);
            $cuttingTickets = $cuttingOrderRecord->cuttingTicket;
            foreach ($cuttingTickets as $ticket) {
                $ticket->delete();
            }
            $date_return = [
                'status' => 'success',
                'data'=> $cuttingOrderRecord,
                'message'=> 'Data Laying Sheet berhasil di hapus',
            ];
            return response()->json($date_return, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    public function refresh_generate_ticket($cutting_order_record_id) {
        try {
            $next_ticket_number = 1;
            $cuttingOrderRecord = CuttingOrderRecord::find($cutting_order_record_id);
            $layingPlanningDetail = $cuttingOrderRecord->layingPlanningDetail;

            $planning_size_list = $layingPlanningDetail->layingPlanningDetailSize;
            $cutting_order_details = $layingPlanningDetail->cuttingOrderRecord->cuttingOrderRecordDetail;

            foreach ($planning_size_list as $planning_size) {
                $ratio_per_size = $planning_size->ratio_per_size;
                for ($i=0; $i < $ratio_per_size; $i++) {
                    foreach($cutting_order_details as $cutting_order_detail) {
                        $data_ticket = [
                            'ticket_number' => $next_ticket_number,
                            'size_id'=> $planning_size->size_id,
                            'layer'=> $cutting_order_detail->layer,
                            'cutting_order_record_id'=> $cutting_order_detail->cuttingOrderRecord->id,
                            'cutting_order_record_detail_id'=> $cutting_order_detail->id,
                            'table_number'=> $cutting_order_detail->cuttingOrderRecord->layingPlanningDetail->table_number,
                            'fabric_roll'=> $cutting_order_detail->fabric_roll,
                        ];
                        $insertCuttingTicket = CuttingTicket::create($data_ticket);
                        $insertCuttingTicket->serial_number = $this->generate_ticket_number($insertCuttingTicket->id);
                        $insertCuttingTicket->save();
                        $next_ticket_number++;
                    }
                }
            }
            $date_return = [
                'status' => 'success',
                'data'=> $cuttingOrderRecord,
                'message'=> 'Data Laying Sheet berhasil di hapus',
            ];
            return response()->json($date_return, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }

    }

    public function refresh_ticket($id) {
        try {
            $cuttingOrderRecord = CuttingOrderRecord::find($id);
            $cuttingTickets = $cuttingOrderRecord->cuttingTicket;
            foreach ($cuttingTickets as $ticket) {
                $ticket->delete();
            }
            $this->refresh_generate_ticket($cuttingOrderRecord->id);
            $date_return = [
                'status' => 'success',
                'data'=> $cuttingOrderRecord,
                'message'=> 'Data Cutting Ticket berhasil di refresh',
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
