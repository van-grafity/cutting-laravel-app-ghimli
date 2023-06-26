<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CuttingTicket;
use App\Models\CuttingOrderRecord;
use App\Models\LayingPlanningDetail;
use App\Models\LayingPlanningDetailSize;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

use Yajra\Datatables\Datatables;
use PDF;

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
                'id' => $ticket->id,
                'no' => Str::padleft($key + 1,2, '0', true),
                'ticket_number' => $this->generate_ticket_number($ticket->id),
                'no_laying_sheet' => $ticket->cuttingOrderRecord->layingPlanningDetail->no_laying_sheet,
                'table_number' => Str::padLeft($ticket->cuttingOrderRecord->layingPlanningDetail->table_number, 3, '0'),
                'color' => $ticket->cuttingOrderRecord->layingPlanningDetail->layingPlanning->color->color,
                'size' => $ticket->size->size,
                'layer' => $ticket->layer,
            ];
        }

        $serial_number = $get_cutting_tickets[0]->cuttingOrderRecord->serial_number;

        return view('page.cutting-ticket.ticket-list-by-cor',compact('tickets', 'serial_number', 'id'));
    }

    public function dataCuttingTicket(){
        $query = CuttingOrderRecord::with(['CuttingTicket', 'CuttingTicket.size'])
        ->select('cutting_order_records.*')
        ->whereHas('CuttingTicket')
        ->get();
            return Datatables::of($query)
            ->escapeColumns([])
            ->addColumn('ticket_number', function($data){
                return $data->serial_number;
            })
            ->addColumn('table_number', function($data){
                return $data->layingPlanningDetail->table_number;
            })
            ->addColumn('action', function($data){
                return '
                <a href="'.route('cutting-ticket.print-multiple', $data->id).'"  target="_blank"class="btn btn-primary btn-sm btn-print-ticket">Print</a>
                <a href="'.route('cutting-ticket.detail', $data->id).'" class="btn btn-info btn-sm">Detail</a>
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
        $data = CuttingOrderRecord::with(['layingPlanningDetail', 'cuttingOrderRecordDetail'])
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
        // ***************************************************************
        /*  1. Ambil Semua Size yang ada di cutting order record ini
            2. Lalu ambil nilai rasio tiap masing masing size
            3. Ambil data detail di Cutting Order Record ini. karena setiap detail di input berdasarkan fabric roll
            4. Tikcet digunakan untuk menandakan atau mengikat kain dari tiap tiap roll.
            5. Ticket di generate berdasarkam Cutting Order Recordnya
        */
        // ***************************************************************

        try {

            $get_last_ticket = CuttingTicket::orderBy('ticket_number', 'asc')->first();
            $next_ticket_number = $get_last_ticket ? $get_last_ticket->ticket_number + 1 : 1;
            $cuttingOrderRecord = CuttingOrderRecord::find($request->cutting_order_id);
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

    public function print_report_pdf($id) {
        $cuttingOrderRecord = CuttingOrderRecord::with(['layingPlanningDetail', 'layingPlanningDetail.layingPlanningDetailSize.size', 'cuttingOrderRecordDetail', 'cuttingOrderRecordDetail.color', 'cuttingTicket', 'cuttingTicket.cuttingOrderRecordDetail', 'cuttingTicket.cuttingOrderRecordDetail.color', 'cuttingTicket.size'])
        ->whereHas('cuttingTicket', function($q) use ($id) {
            $q->where('cutting_order_record_id', $id);
        })->first();
        $cuttingTickets = $cuttingOrderRecord->cuttingTicket;
        foreach ($cuttingTickets as $ticket) {
            $ticket->ticket_number = $this->generate_ticket_number($ticket->id);
        }
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
            // 'gl' => $gl,
            // 'style' => $style,
            'color' => $color,
            // 'buyer' => $buyer,
        ];
        $customPaper = array(0,0,794.00, 612.00);
        $pdf = PDF::loadview('page.cutting-ticket.report', compact('data'))->setPaper($customPaper, 'portrait');
        return $pdf->stream('PackingList' . '.pdf');
    }

    public function print_ticket(Request $request, $ticket_id) {

        $ticket = CuttingTicket::find($ticket_id);
        $filename = $ticket->serial_number . '.pdf';

        $layingPlanningDetail = $ticket->cuttingOrderRecord->layingPlanningDetail;


        $data = (object)[
            'serial_number' => $this->generate_ticket_number($ticket->id),
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
        // $data = [
        //     (object)[
        //         "serial_number"=> "CT-62843-MHG-001-001",
        //         "buyer"=> "Aeropostale",
        //         "size"=> "XS",
        //         "color"=> "MHG",
        //         "ticket_number"=> "001",
        //         "layer"=> 11
        //     ],
        //     (object)[
        //         "serial_number"=> "CT-62843-MHG-001-004",
        //         "buyer"=> "Aeropostale",
        //         "size"=> "XS",
        //         "color"=> "MHG",
        //         "ticket_number"=> "004",
        //         "layer"=> 13
        //     ],
        // ];

        $data = [];
        foreach ($cutting_tickets as $ticket) {
            $layingPlanningDetail = $ticket->cuttingOrderRecord->layingPlanningDetail;
            $data[] = (object)[
                'serial_number' => $this->generate_ticket_number($ticket->id),
                'buyer' => $layingPlanningDetail->layingPlanning->gl->buyer->name,
                'size' => $ticket->size->size,
                'color' => $layingPlanningDetail->layingPlanning->color->color,
                'ticket_number' => Str::padLeft($ticket->ticket_number, 3, '0'),
                'layer' => $ticket->layer,
            ];
        }
        
        // 10.1 cm x 6.3 cm
        $customPaper = array(0,0,180.00, 298.00);
        $pdf = PDF::loadview('page.cutting-ticket.print-all', compact('data'))->setPaper($customPaper, 'landscape');
        return $pdf->stream($filename);
    }

    // private function
    function generate_ticket_number($ticket_id) {
        $ticket = CuttingTicket::find($ticket_id);

        $gl_number = $ticket->cuttingOrderRecord->layingPlanningDetail->layingPlanning->gl->gl_number;
        // $gl_number = explode('-', $gl_number)[0];
        
        $color_code = $ticket->cuttingOrderRecord->layingPlanningDetail->layingPlanning->color->color_code;

        $table_number = $ticket->cuttingOrderRecord->layingPlanningDetail->table_number;
        $table_number = Str::padLeft($table_number, 3, '0');
        
        $ticket_number = Str::padLeft($ticket->ticket_number, 3, '0');
        
        return "CT-{$gl_number}-{$color_code}-{$table_number}-{$ticket_number}";
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
}
