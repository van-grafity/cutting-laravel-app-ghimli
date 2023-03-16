<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CuttingTicket;
use App\Models\LayingPlanningDetail;
use App\Models\LayingPlanningDetailSize;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class CuttingTicketsController extends Controller
{
    public function index()
    {
        $get_cutting_tickets = CuttingTicket::all();

        $tickets = [];
        foreach ($get_cutting_tickets as $key => $ticket) {
            $tickets[] = (object)[
                'id' => $ticket->id,
                'no' => Str::padleft($key + 1,2, '0', true),
                'ticket_number' => $this->generate_ticket_number($ticket->id),
                'no_laying_sheet' => $ticket->cuttingOrderRecord->layingPlanningDetail->no_laying_sheet,
                'table_number' => Str::padLeft($ticket->cuttingOrderRecord->layingPlanningDetail->table_number, 3, '0'),
                'gl_number' => $ticket->cuttingOrderRecord->layingPlanningDetail->layingPlanning->gl->gl_number,
                'color' => $ticket->cuttingOrderRecord->layingPlanningDetail->layingPlanning->color->color,
                'size' => $ticket->size->size,
                'layer' => $ticket->layer,
            ];
        }
        return view('page.cutting-ticket.index',compact('tickets'));
    }

    public function create() {
        $no_laying_sheet_list = LayingPlanningDetail::select('id','no_laying_sheet')->get();
        return view('page.cutting-ticket.add', compact('no_laying_sheet_list'));
    }

    public function show($id){
        $data = CuttingTicket::find($id);
        try {
            $ticket = CuttingTicket::find($id);
            $data = [
                'id' => $ticket->id,
                'ticket_number' => $this->generate_ticket_number($ticket->id),
                'no_laying_sheet' => $ticket->cuttingOrderRecord->layingPlanningDetail->no_laying_sheet,
                'table_number' => Str::padLeft($ticket->cuttingOrderRecord->layingPlanningDetail->table_number, 3, '0'),
                'gl_number' => $ticket->cuttingOrderRecord->layingPlanningDetail->layingPlanning->gl->gl_number,
                'buyer' => $ticket->cuttingOrderRecord->layingPlanningDetail->layingPlanning->buyer->name,
                'style' => $ticket->cuttingOrderRecord->layingPlanningDetail->layingPlanning->style->style,
                'color' => $ticket->cuttingOrderRecord->layingPlanningDetail->layingPlanning->color->color,
                'size' => $ticket->size->size,
                'layer' => $ticket->layer,
                'fabric_roll' => $ticket->cuttingOrderRecordDetail->fabric_roll,
                'fabric_po' => $ticket->cuttingOrderRecord->layingPlanningDetail->layingPlanning->fabric_po,
                'fabric_type' => $ticket->cuttingOrderRecord->layingPlanningDetail->layingPlanning->fabricType->description,
                'fabric_cons' => $ticket->cuttingOrderRecord->layingPlanningDetail->layingPlanning->fabricCons->description,
            ];
            return response()->json($data, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    public function get_laying_sheet($laying_planning_detail_id)
    {
        try {
            $layingPlanningDetail = LayingPlanningDetail::find($laying_planning_detail_id);
            
            $data = [
                'laying_planning_detail_id' => $layingPlanningDetail->id,
                'gl_number' => $layingPlanningDetail->layingPlanning->gl->gl_number,
                'table_number' => $layingPlanningDetail->table_number,
                'style' => $layingPlanningDetail->layingPlanning->style->style,
                'color' => $layingPlanningDetail->layingPlanning->color->color,
                'buyer' => $layingPlanningDetail->layingPlanning->buyer->name,
            ];

            $get_size_ratio = LayingPlanningDetailSize::where('laying_planning_detail_id', $laying_planning_detail_id)->get();
            $size_ratio = [];

            foreach( $get_size_ratio as $key => $size ) {
                $size_ratio[] = $size->size->size . " = " . $size->ratio_per_size;
            }
            $size_ratio = Arr::join($size_ratio, ' | ');
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
        // ***************************************************************
        /*  1. Ambil Semua Size yang ada di cutting order record ini
            2. Lalu ambil nilai rasio tiap masing masing size
            3. Ambil data detail di Cutting Order Record ini. karena setiap detail di input berdasarkan fabric roll
            4. Tikcet digunakan untuk menandakan atau mengikat kain dari tiap tiap roll.
            5. Ticket di generate berdasarkam Cutting Order Recordnya
        */
        // ***************************************************************

        try {

            $get_last_ticket = CuttingTicket::orderBy('ticket_number', 'desc')->first();
            $next_ticket_number = $get_last_ticket ? $get_last_ticket->ticket_number + 1 : 1;
            $layingPlanningDetail = LayingPlanningDetail::find($request->laying_planning_detail_id);
            
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
                        $next_ticket_number++;
                    }
                }
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
        
        return redirect()->route('cutting-ticket.index');
    }



    // private function
    function generate_ticket_number($ticket_id) {
        $ticket = CuttingTicket::find($ticket_id);

        $gl_number = $ticket->cuttingOrderRecord->layingPlanningDetail->layingPlanning->gl->gl_number;
        $gl_number = explode('-', $gl_number)[0];
        
        $table_number = $ticket->cuttingOrderRecord->layingPlanningDetail->table_number;
        $table_number = Str::padLeft($table_number, 3, '0');
        
        $ticket_number = Str::padLeft($ticket->ticket_number, 3, '0');
        
        return "CT-{$gl_number}-{$table_number}-{$ticket_number}";
    }

}
