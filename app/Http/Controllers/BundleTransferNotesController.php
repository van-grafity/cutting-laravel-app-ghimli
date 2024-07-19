<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\BundleTransferNote;
use App\Models\BundleTransferNoteDetail;
use App\Models\LayingPlanningDetail;

use Yajra\Datatables\Datatables;
use Illuminate\Support\Arr;

use Carbon\Carbon;
use PDF;
use DB;

class BundleTransferNotesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return view('page.bundle-transfer-note.index');
    }

    public function dataTransferNote()
    {
        $query = DB::table('bundle_transfer_notes')
            ->join('bundle_locations','bundle_locations.id','=','bundle_transfer_notes.location_to_id')
            ->select('bundle_transfer_notes.id as transfer_note_id','bundle_transfer_notes.serial_number','bundle_locations.location as location','bundle_transfer_notes.created_at')
            ->get();

            return Datatables::of($query)
            ->escapeColumns([])
            ->addColumn('action', function($data){
                $action = '<a href="'.route('bundle-transfer-note.detail',$data->transfer_note_id).'" class="btn btn-info btn-sm mb-1" data-toggle="tooltip" data-placement="top" title="Detail" target="_blank">Detail</a>';
                return $action;

            })->addColumn('date', function($data){
                return Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d-m-Y');

            })->addColumn('gl_number', function($data){
                return $this->getGlFromTransferNoteID($data->transfer_note_id);

            })->addColumn('color', function($data){
                return $this->getColorFromTrasnferNoteID($data->transfer_note_id);

            })->addColumn('total_pcs', function($data){
                return $this->getTotalPcsFromTrasnferNoteID($data->transfer_note_id);

            })
            ->addIndexColumn()
            ->make(true);
    }

    private function getGlFromTransferNoteID($transfer_note_id)
    {
        $transfer_note_detail = BundleTransferNoteDetail::join('bundle_stock_transactions','bundle_stock_transactions.id','=','bundle_transfer_note_details.bundle_transaction_id')
            ->join('cutting_tickets','cutting_tickets.id','=','bundle_stock_transactions.ticket_id')
            ->join('cutting_order_records','cutting_order_records.id','=','cutting_tickets.cutting_order_record_id')
            ->join('laying_planning_details','laying_planning_details.id','=','cutting_order_records.laying_planning_detail_id')
            ->join('laying_plannings','laying_plannings.id','=','laying_planning_details.laying_planning_id')
            ->join('gls','gls.id','=','laying_plannings.gl_id')
            ->where('bundle_transfer_note_id',$transfer_note_id)
            ->groupBy('gls.id')
            ->select('gls.gl_number')->get()->toArray();

        $gl_number_list = array_column($transfer_note_detail,'gl_number');
        $gl_number_list = implode(', ',$gl_number_list);
        return $gl_number_list;
    }

    private function getColorFromTrasnferNoteID($transfer_note_id)
    {
        $transfer_note_detail = BundleTransferNoteDetail::join('bundle_stock_transactions','bundle_stock_transactions.id','=','bundle_transfer_note_details.bundle_transaction_id')
            ->join('cutting_tickets','cutting_tickets.id','=','bundle_stock_transactions.ticket_id')
            ->join('cutting_order_records','cutting_order_records.id','=','cutting_tickets.cutting_order_record_id')
            ->join('laying_planning_details','laying_planning_details.id','=','cutting_order_records.laying_planning_detail_id')
            ->join('laying_plannings','laying_plannings.id','=','laying_planning_details.laying_planning_id')
            ->join('colors','colors.id','=','laying_plannings.color_id')
            ->where('bundle_transfer_note_id',$transfer_note_id)
            ->groupBy('colors.id')
            ->select('colors.color')->get()->toArray();

        $color_list = array_column($transfer_note_detail,'color');
        $color_list = implode(', ',$color_list);
        return $color_list;
    }

    private function getTotalPcsFromTrasnferNoteID($transfer_note_id)
    {
        $transfer_note_detail = BundleTransferNoteDetail::join('bundle_stock_transactions','bundle_stock_transactions.id','=','bundle_transfer_note_details.bundle_transaction_id')
            ->join('cutting_tickets','cutting_tickets.id','=','bundle_stock_transactions.ticket_id')
            ->where('bundle_transfer_note_id',$transfer_note_id)
            ->select('cutting_tickets.layer')->get()->toArray();

        $layer_list = array_column($transfer_note_detail,'layer');
        $total_pcs = array_sum($layer_list);
        return $total_pcs;
    }

    private function getStyleFromTrasnferNoteID($transfer_note_id)
    {
        $transfer_note_detail = BundleTransferNoteDetail::join('bundle_stock_transactions','bundle_stock_transactions.id','=','bundle_transfer_note_details.bundle_transaction_id')
            ->join('cutting_tickets','cutting_tickets.id','=','bundle_stock_transactions.ticket_id')
            ->join('cutting_order_records','cutting_order_records.id','=','cutting_tickets.cutting_order_record_id')
            ->join('laying_planning_details','laying_planning_details.id','=','cutting_order_records.laying_planning_detail_id')
            ->join('laying_plannings','laying_plannings.id','=','laying_planning_details.laying_planning_id')
            ->join('styles','styles.id','=','laying_plannings.style_id')
            ->where('bundle_transfer_note_id',$transfer_note_id)
            ->groupBy('styles.id')
            ->select('styles.style')->get()->toArray();

        $style_list = array_column($transfer_note_detail,'style');
        $style = implode(', ', $style_list);
        return $style;
    }

    public function detail(Request $request, $transfer_note_id)
    {
        $data = $this->getTransferNoteData($transfer_note_id);
        return view('page.bundle-transfer-note.detail', $data);
    }

    public function print(Request $request, $transfer_note_id)
    {
        $data = $this->getTransferNoteData($transfer_note_id);
        $filename = 'Cut Piece Transfer Note No. '.$data['transfer_note_header']->serial_number.'.pdf';
        $data['filename'] = $filename;
        // return view('page.bundle-transfer-note.print', $data);

        $pdf = PDF::loadView('page.bundle-transfer-note.print', $data)->setPaper('a5', 'landscape');
        return $pdf->stream($filename);
    }

    private function getTransferNoteData($transfer_note_id)
    {
        $transfer_note = BundleTransferNote::with('bundleLocationTo')->find($transfer_note_id);
        $size_list = $this->getSizeList($transfer_note->id);

        $transfer_note_header = (object)[
            'transfer_note_id' => $transfer_note->id,
            'serial_number' => $transfer_note->serial_number,
            'location' => $transfer_note->bundleLocationTo->location,
            'style_no' => $this->getStyleFromTrasnferNoteID($transfer_note->id),
            'gl_number' => $this->getGlFromTransferNoteID($transfer_note->id),
            'date' => $transfer_note->created_at->format('d-m-Y'),
        ];

        $transfer_note_detail = BundleTransferNoteDetail::join('bundle_stock_transactions','bundle_stock_transactions.id','=','bundle_transfer_note_details.bundle_transaction_id')
            ->join('cutting_tickets','cutting_tickets.id','=','bundle_stock_transactions.ticket_id')
            ->join('cutting_order_records','cutting_order_records.id','=','cutting_tickets.cutting_order_record_id')
            ->join('laying_planning_details','laying_planning_details.id','=','cutting_order_records.laying_planning_detail_id')
            ->join('laying_plannings','laying_plannings.id','=','laying_planning_details.laying_planning_id')
            ->join('colors','colors.id','=','laying_plannings.color_id')
            ->where('bundle_transfer_note_id', $transfer_note->id)
            ->groupBy('cutting_order_records.id')
            ->select('bundle_transfer_note_id','laying_planning_details.id as laying_planning_detail_id','laying_planning_details.table_number','cutting_order_records.id as cor_id','cutting_order_records.serial_number as cor_number', 'colors.color')->get();

        foreach ($transfer_note_detail as $key => $cor) {

            $summary_per_size = BundleTransferNoteDetail::join('bundle_stock_transactions','bundle_stock_transactions.id','=','bundle_transfer_note_details.bundle_transaction_id')
                ->join('cutting_tickets','cutting_tickets.id','=','bundle_stock_transactions.ticket_id')
                ->join('sizes','sizes.id','=','cutting_tickets.size_id')
                ->where('cutting_tickets.cutting_order_record_id', $cor->cor_id)
                ->groupBy('sizes.id')
                ->select('sizes.id as size_id','sizes.size', DB::raw('SUM(cutting_tickets.layer) as qty'))->get();

            foreach ($size_list as $key_size => $size) {
                $qty_per_size[$key_size] = (object)[
                    'id' => $size->id,
                    'size' => $size->size
                ];
                $size_id = $size->id;
                $filtered_result = $summary_per_size->filter(function ($summary, $key) use ($size_id) {
                    return $summary->size_id === $size_id;
                });

                if($filtered_result->isNotEmpty()){
                    $qty_per_size[$key_size]->qty = $filtered_result->first()->qty;
                } else {
                    $qty_per_size[$key_size]->qty = 0;
                }
            }

            $transfer_note_detail[$key]->qty_per_size = $qty_per_size;

            $total_qty_all_size = $summary_per_size->reduce(function ($carry, $item) {
                return $carry + $item->qty;
            }, 0);
            $transfer_note_detail[$key]->total_qty = $total_qty_all_size;
        }

        $data = [
            'transfer_note_header' => $transfer_note_header,
            'size_list' => $size_list,
            'transfer_note_detail' => $transfer_note_detail,
        ];

        return $data;
    }

    private function getSizeList($transfer_note_id) : object
    {
        return BundleTransferNoteDetail::join('bundle_stock_transactions','bundle_stock_transactions.id','=','bundle_transfer_note_details.bundle_transaction_id')
            ->join('cutting_tickets','cutting_tickets.id','=','bundle_stock_transactions.ticket_id')
            ->join('sizes','sizes.id','=','cutting_tickets.size_id')
            ->groupBy('sizes.id')
            ->select('sizes.id','sizes.size')
            ->get();
    }
}
