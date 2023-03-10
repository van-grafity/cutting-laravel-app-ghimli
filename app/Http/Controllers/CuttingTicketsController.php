<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CuttingTicket;
use Illuminate\Support\Str;

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
        // dd($tickets);
        return view('page.cutting-ticket.index',compact('tickets'));
    }

    function generate_ticket_number($ticket_id) {
        $ticket = CuttingTicket::find($ticket_id);

        $gl_number = $ticket->cuttingOrderRecord->layingPlanningDetail->layingPlanning->gl->gl_number;
        $gl_number = explode('-', $gl_number)[0];
        
        $table_number = $ticket->cuttingOrderRecord->layingPlanningDetail->table_number;
        $table_number = Str::padLeft($table_number, 3, '0');
        
        $ticket_number = Str::padLeft($ticket->ticket_number, 3, '0');
        
        return "CT-{$gl_number}-{$table_number}-{$ticket_number}";
    }
    // public function create()
    // {
    //     return view('page.cutting-ticket.index');
    // }

    public function createTicket() {
        return view('page.cutting-ticket.add');
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

}
