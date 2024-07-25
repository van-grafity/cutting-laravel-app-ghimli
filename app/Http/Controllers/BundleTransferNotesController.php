<?php

namespace App\Http\Controllers;

use App\Models\BundleStockTransaction;
use Illuminate\Http\Request;

use App\Models\BundleTransferNote;
use App\Models\BundleTransferNoteDetail;
use App\Models\LayingPlanningDetail;

use Yajra\Datatables\Datatables;
use Illuminate\Support\Arr;
use Toastr;
use Carbon\Carbon;
use PDF;
use DB;
use Illuminate\Support\Facades\Auth;

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

    public function edit($id){
        $transfer_note = BundleTransferNote::with('bundleLocationTo')->findOrFail($id);
        $location = DB::table('bundle_locations')->get();
        $transfer_note_header = (object)[
            'transfer_note_id' => $transfer_note->id,
            'serial_number' => $transfer_note->serial_number,
            'location' => $transfer_note->bundleLocationTo->location,
            'style_no' => $this->getStyleFromTrasnferNoteID($transfer_note->id),
            'color' => $this->getColorFromTrasnferNoteID($transfer_note->id),
            'gl_number' => $this->getGlFromTransferNoteID($transfer_note->id),
            'date' => $transfer_note->created_at->format('d-m-Y'),
        ];
        $data = [
            "transfer_note" => $transfer_note_header,
             "total_pcs" => $this->getTotalPcsFromTrasnferNoteID($id),
             "location" => $location
        ];
        // dd($data);
        return view('page.bundle-transfer-note.edit', compact("data"));
    }

    public function updateTransferNote($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $updated = BundleTransferNote::where('id', $id)
                    ->update(['location_to_id' => request()->input('location')]);

                if (!$updated) {
                    throw new \Exception('Failed to update BundleTransferNote.');
                }

                $transfer_note_detail = BundleTransferNoteDetail::join('bundle_stock_transactions', 'bundle_stock_transactions.id', '=', 'bundle_transfer_note_details.bundle_transaction_id')
                    ->where('bundle_transfer_note_details.bundle_transfer_note_id', $id)
                    ->select('bundle_stock_transactions.id as bundle_stock_transaction_id')
                    ->get();

                if ($transfer_note_detail->isEmpty()) {
                    throw new \Exception('No BundleTransferNoteDetail found for the given ID.');
                }

                foreach ($transfer_note_detail as $transfer_note) {
                    $bundle_stock_transaction = BundleStockTransaction::where('id', $transfer_note->bundle_stock_transaction_id)->first();
                    $bundle_stock_transaction->location_id = request()->input('location');
                    $bundle_stock_transaction->save();
                }
            });

            // If transaction succeeds, redirect to index
            return redirect()->route('bundle-transfer-note.index');
        } catch (\Exception $e) {
            // If any exception occurs, redirect to edit with error message
            return redirect()->route('bundle-transfer-note.edit', $id)->with('error', $e->getMessage());
        }
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
                $action ='<a href="'.route('bundle-transfer-note.detail',$data->transfer_note_id).'" class="btn btn-info btn-sm mb-1" data-toggle="tooltip" data-placement="top" title="Detail" target="_blank">Detail</a>';
                if (Auth::user()->hasRole('super_admin')) {
                    $action.= '<a href="'.route('bundle-transfer-note.edit',$data->transfer_note_id).'" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Detail" target="_blank">Edit</a>';
                }
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
            ->join('sizes','sizes.id','=','cutting_tickets.size_id')
            ->where('bundle_transfer_note_id', $transfer_note->id)
            ->groupBy('sizes.id', 'sizes.size', 'bundle_transfer_note_id', 'laying_planning_details.id', 'laying_planning_details.table_number', 'cutting_order_records.id', 'cutting_order_records.serial_number', 'colors.color')
            ->select(DB::raw('SUM(cutting_tickets.layer) as qty'),'sizes.id as size_id','sizes.size','bundle_transfer_note_id','laying_planning_details.id as laying_planning_detail_id','laying_planning_details.table_number','cutting_order_records.id as cor_id','cutting_order_records.serial_number as cor_number', 'colors.color')->get();

            foreach ($transfer_note_detail as $key => $transfer_note_details) {
                $qty_per_size = [];
                foreach ($size_list as $key_size => $size) {
                    $qty_per_size[$key_size] = (object)[
                        'id' => $size->id,
                        'size' => $size->size,
                        'qty' => 0
                    ];
                    if($transfer_note_details->size_id === $size->id){
                        $qty_per_size[$key_size]->qty = $transfer_note_details->qty;
                    }
                }
                $transfer_note_detail[$key]->qty_per_size = $qty_per_size;

                $total_qty_all_size = 0;
                foreach ($transfer_note_details->qty_per_size as $qty) {
                    $total_qty_all_size += $qty->qty;
                }
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
            ->where('bundle_transfer_note_details.bundle_transfer_note_id', $transfer_note_id)
            ->groupBy('sizes.id')
            ->select('sizes.id','sizes.size')
            ->orderBy('sizes.id', 'ASC')
            ->get();
    }
}
