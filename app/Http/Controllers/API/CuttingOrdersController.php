<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\CuttingOrderRecord;
use App\Models\CuttingOrderRecordSticker;
use App\Models\CuttingOrderRecordDetail;
use App\Models\LayingPlanningDetail;
use App\Models\LayingPlanning;
use App\Models\Color;
use App\Models\Remark;
use App\Models\StatusLayer;
use App\Models\StatusCut;
use App\Http\Traits\ApiHelpers;

use Carbon\Carbon;

class CuttingOrdersController extends BaseController
{
    use ApiHelpers;

    // public function __construct()
    // {
    //     $this->middleware('auth:api');
    // }

    public function index(Request $request)
    {
        $input = $request->all();
        $limit = $request->input('limit');
        $page = $request->input('page');
        $search = $request->input('s');
        $status_layer = $request->input('status_layer');
        $status_cut = $request->input('status_cut');
        
        $cuttingOrderRecord = CuttingOrderRecord::with('statusLayer', 'statusCut', 'cuttingOrderRecordDetail.color', 'layingPlanningDetail.layingPlanning.style.gl.buyer', 'layingPlanningDetail.layingPlanning.color')
        ->where(function ($query) use ($search) {
            $query->where('serial_number', 'like', '%' . $search . '%');
        })
        ->where(function ($query) use ($status_layer) {
            if ($status_layer != null) {
                $query->where('id_status_layer', $status_layer);
            }
        })
        ->where(function ($query) use ($status_cut) {
            if ($status_cut != null) {
                $query->where('id_status_cut', $status_cut);
            }
        })
        // ->where(function ($query) {
        //     $query->where('created_at', '>=', Carbon::create(2023, 9, 1, 0, 0, 0));
        // })
        ->latest()
        ->paginate($limit, ['*'], 'page', $page);
        
        $pagination = [
            'current_page' => $cuttingOrderRecord->currentPage(),
            'last_page' => $cuttingOrderRecord->lastPage(),
            'prev_page_url' => $cuttingOrderRecord->previousPageUrl(),
            'next_page_url' => $cuttingOrderRecord->nextPageUrl(),
            'total' => $cuttingOrderRecord->total(),
        ];
        
        $cuttingOrderRecords = [
            'cutting_order_records' => $cuttingOrderRecord->items()
        ];

        $result = array_merge($cuttingOrderRecords, $pagination);

        return $this->onSuccess($result, 'Cutting Order Record retrieved successfully.');
    }

    public function show($serial_number)
    {
        $getCuttingOrder = CuttingOrderRecord::with('CuttingOrderRecordDetail')->where('serial_number', $serial_number)->latest()->first();
        if ($getCuttingOrder == null) return $this->onError(404, 'Cutting Order Record not found.');
        $layingPlanningDetail = LayingPlanningDetail::where('id', $getCuttingOrder->laying_planning_detail_id)->first();
        // $layingPlanning = LayingPlanning::with('layingPlanningDetail.cuttingOrderRecord')->where('id', $layingPlanningDetail->laying_planning_id)->first();

        // foreach ($layingPlanning->layingPlanningDetail as $detail) {
        //     if ($detail->marker_code == 'PILOT RUN' && $detail->cuttingOrderRecord->is_pilot_run == 0) {
        //         return $this->onError(404, 'Pilot Run must be approved first.');
        //     }
        // }
        
        $cuttingRecordDetail = CuttingOrderRecordDetail::with('CuttingOrderRecord')->whereHas('CuttingOrderRecord', function ($query) use ($serial_number) {
            $query->where('serial_number', $serial_number);
        })->get();

        if ($getCuttingOrder->id_status_layer == 1 && $getCuttingOrder->id_status_cut == 1) {
            if ($getCuttingOrder->cuttingOrderRecordDetail != null) {
                $getCuttingOrder->id_status_layer = 4;
            }
        } else {
            $getCuttingOrder->id_status_layer = $getCuttingOrder->id_status_layer;
        }

        $getCuttingOrder->save();
        
        $data = collect(
            [
                'cutting_order_record_details' => $cuttingRecordDetail,
                // 'laying_planning' => $layingPlanning
            ]
        );
        return $this->onSuccess($data, 'Cutting Order Record retrieved successfully.');
    }
    
    public function store(Request $request)
    {
        $input = $request->all();
        $cuttingOrderRecord = CuttingOrderRecord::with('CuttingOrderRecordDetail')->where('serial_number', $input['serial_number'])->first();
        $sum_layer = 0;
        foreach ($cuttingOrderRecord->cuttingOrderRecordDetail as $detail) {
            $sum_layer += $detail->layer;
        }
        $cuttingOrderRecordDetail = new CuttingOrderRecordDetail;
        
        $cuttingOrderRecordDetail->cutting_order_record_id = $cuttingOrderRecord->id;
        $cuttingOrderRecordDetail->fabric_roll = $input['fabric_roll'];
        $cuttingOrderRecordDetail->fabric_batch = $input['fabric_batch'];

        $color = Color::where('color', $input['color'])->first();
        if ($color == null) return $this->onError(404, 'Color not found.'); // ?
        $cuttingOrderRecordDetail->color_id = $color->id;

        $cuttingOrderRecordDetail->yardage = $input['yardage'];
        $cuttingOrderRecordDetail->weight = $input['weight'];
        $cuttingOrderRecordDetail->layer = $input['layer'];
        $cuttingOrderRecordDetail->joint = $input['joint'];
        $cuttingOrderRecordDetail->balance_end = $input['balance_end'];

        $cuttingOrderRecordDetail->remarks = $input['remarks'];
        $cuttingOrderRecordDetail->operator = $input['operator'];
        $cuttingOrderRecordDetail->user_id = $input['user_id'];

        $sum_layer += $input['layer'];
        // if ($sum_layer == $cuttingOrderRecord->layingPlanningDetail->layer_qty) {
        //     $status = StatusLayer::where('name', 'completed')->first();
        //     if ($status == null) return $this->onError(404, 'Status Layer Cut not found.');
        //     $cuttingOrderRecord->id_status_layer = $status->id;
        // } else if ($sum_layer > $cuttingOrderRecord->layingPlanningDetail->layer_qty) {
        //     return $this->onSuccess(null, 'Layer Cut tidak boleh lebih dari Layer Qty.');
        //     $status = StatusLayer::where('name', 'over layer')->first();
        //     if ($status == null) return $this->onError(404, 'Status Layer Cut not found.');
        //     $cuttingOrderRecord->id_status_layer = $status->id;
        // } else {
        //     $status = StatusLayer::where('name', 'not completed')->first();
        //     if ($status == null) return $this->onError(404, 'Status Layer Cut not found.');
        //     $cuttingOrderRecord->id_status_layer = $status->id;
        // }

        $max_min = $cuttingOrderRecord->layingPlanningDetail->layer_qty * 0.03;
        $max_min = round($max_min, 0, PHP_ROUND_HALF_UP);
        if ($sum_layer < $cuttingOrderRecord->layingPlanningDetail->layer_qty + $max_min && $sum_layer >= $cuttingOrderRecord->layingPlanningDetail->layer_qty || $sum_layer == $cuttingOrderRecord->layingPlanningDetail->layer_qty || $sum_layer == $cuttingOrderRecord->layingPlanningDetail->layer_qty + $max_min) {
            $status = StatusLayer::where('name', 'completed')->first();
            if ($status == null) return $this->onError(404, 'Status Layer Cut not found.');
            $cuttingOrderRecord->id_status_layer = $status->id;
             $cuttingOrderRecord->layer = Carbon::now();
        } else if ($sum_layer > $cuttingOrderRecord->layingPlanningDetail->layer_qty + $max_min) {
            return $this->onSuccess(null, 'Jumlah layer tidak boleh lebih, max 3% dari layer planning.');
            $status = StatusLayer::where('name', 'over layer')->first();
            if ($status == null) return $this->onError(404, 'Status Layer Cut not found.');
            $cuttingOrderRecord->id_status_layer = $status->id;
        } else {
            $status = StatusLayer::where('name', 'not completed')->first();
            if ($status == null) return $this->onError(404, 'Status Layer Cut not found.');
            $cuttingOrderRecord->id_status_layer = $status->id;
        }
        
        if ($cuttingOrderRecord->id_status_layer == 1 && $cuttingOrderRecord->id_status_cut == 1) {
            if ($cuttingOrderRecord->cuttingOrderRecordDetail != null) {
                $cuttingOrderRecord->id_status_layer = 4;
            }
        } else {
            $cuttingOrderRecord->id_status_layer = $cuttingOrderRecord->id_status_layer;
        }
        
        $cuttingOrderRecordDetail->save();
        $cuttingOrderRecord->save();
        $data = CuttingOrderRecord::where('cutting_order_records.id', $cuttingOrderRecord->id)->with('statusLayer', 'cuttingOrderRecordDetail.user')
            ->first();
        $data = collect(
            [
                'cutting_order_record' => $data
            ]
        );
        return $this->onSuccess($data, 'Fabric roll berhasil di masukkan ke cutting order record.');
    }
    
    public function uploadStickerFabric(Request $request)
    {
        $input = $request->all();
        $cuttingOrderRecord = CuttingOrderRecord::where('serial_number', $input['serial_number'])->first();
        if ($cuttingOrderRecord == null) return $this->onError(404, 'Cutting Order Record not found.');
        $cuttingOrderSticker = new CuttingOrderRecordSticker;
        $cuttingOrderSticker->cutting_order_record_id = $cuttingOrderRecord->id;
        $cuttingOrderSticker->photo = $request->photo->move(public_path('images'), $request->photo->getClientOriginalName());
        $cuttingOrderSticker->save();
        $data = CuttingOrderRecord::where('cutting_order_records.id', $cuttingOrderRecord->id)->with('cuttingOrderRecordSticker')
            ->first();
        $data = collect(
            [
                'cutting_order_record' => $data
            ]
        );
        return $this->onSuccess($data, 'Sticker Fabric uploaded successfully.');
    }

    public function checkRangeWithinRadius(Request $request)
    {
        $currentLatitude = 1.107723; // $request->input('current_latitude')
        $currentLongitude = 104.071668; // $request->input('current_longitude')

        $targetLatitude = 1.107893; // $request->input('target_latitude')
        $targetLongitude = 104.071576; // $request->input('target_longitude')

        // if range is outside target latitude and longitude from diameter 30 meter
        if ($currentLatitude > $targetLatitude + 0.00026949458 || $currentLatitude < $targetLatitude - 0.00026949458 || $currentLongitude > $targetLongitude + 0.00026949458 || $currentLongitude < $targetLongitude - 0.00026949458) {
            return $this->onSuccess(404, 'Range is outside target latitude and longitude from diameter 30 meter.');
        }

        return $this->onSuccess(null, 'Range is within target latitude and longitude from diameter 30 meter.');
    }


    public function search(Request $request)
    {
        $input = $request->all();
        $cuttingOrderRecord = CuttingOrderRecord::with('statusLayer', 'cuttingOrderRecordDetail', 'cuttingOrderRecordDetail.color')
        ->where('serial_number', 'like', '%' . $input['serial_number'] . '%')
        ->get();
        
        $data = collect(
            [
                'cutting_order_records' => $cuttingOrderRecord
            ]
        );
        return $this->onSuccess($data, 'Cutting Order Record retrieved successfully.');
    }

    public function color()
    {
        $data = Color::all();
        $data = collect(
            [
                'color' => $data
            ]
        );
        return $this->onSuccess($data, 'Color retrieved successfully.');
    }
    
    public function update(Request $request, $id)
    {
        //
    }

    public function updateFabricRollByCorId(Request $request, $id)
    {
        try {
            $input = $request->only([
                'fabric_roll', 'fabric_batch', 'color_id', 'yardage',
                'weight', 'layer', 'joint', 'balance_end', 'remarks', 'operator', 'user_id'
            ]);
            $cuttingOrderRecordDetail = CuttingOrderRecordDetail::with('cuttingOrderRecord.statusCut')->where('id', $id)->first();
            if ($cuttingOrderRecordDetail->cuttingOrderRecord->statusCut->id == 2) {
                return $this->onSuccess(collect(['cutting_order_record_detail' => $cuttingOrderRecordDetail]), 'Cannot update fabric roll, cutting order record has been cut.');
            }
            $cuttingOrderRecordDetail->cutting_order_record_id = $cuttingOrderRecordDetail->cuttingOrderRecord->id;
            $cuttingOrderRecordDetail->fabric_roll = $input['fabric_roll'];
            $cuttingOrderRecordDetail->fabric_batch = $input['fabric_batch'];

            $color = Color::where('id', $input['color_id'])->first();
            if ($color == null) return $this->onError(404, 'Color not found.');
            $cuttingOrderRecordDetail->color_id = $color->id;

            $cuttingOrderRecordDetail->yardage = $input['yardage'];
            $cuttingOrderRecordDetail->weight = $input['weight'];
            $cuttingOrderRecordDetail->layer = $input['layer'];
            $cuttingOrderRecordDetail->joint = $input['joint'];
            $cuttingOrderRecordDetail->balance_end = $input['balance_end'];

            $cuttingOrderRecordDetail->remarks = $input['remarks'];
            $cuttingOrderRecordDetail->operator = $input['operator'];
            $cuttingOrderRecordDetail->user_id = $input['user_id'];

            $sum_layer = 0;
            $cuttingOrderRecord = $cuttingOrderRecordDetail->cuttingOrderRecord;
            $sum_layer += $input['layer'];
            // not using max min

            if ($sum_layer == $cuttingOrderRecord->layingPlanningDetail->layer_qty) {
                $status = StatusLayer::where('name', 'completed')->first();
                if ($status == null) return $this->onError(404, 'Status Layer Cut not found.');
                $cuttingOrderRecord->id_status_layer = $status->id;
            } else if ($sum_layer > $cuttingOrderRecord->layingPlanningDetail->layer_qty) {
                return $this->onSuccess(null, 'Layer Cut tidak boleh lebih dari Layer Qty.');
                $status = StatusLayer::where('name', 'over layer')->first();
                if ($status == null) return $this->onError(404, 'Status Layer Cut not found.');
                $cuttingOrderRecord->id_status_layer = $status->id;
            } else {
                $status = StatusLayer::where('name', 'not completed')->first();
                if ($status == null) return $this->onError(404, 'Status Layer Cut not found.');
                $cuttingOrderRecord->id_status_layer = $status->id;
            }

            if ($cuttingOrderRecord->id_status_layer == 1 && $cuttingOrderRecord->id_status_cut == 1) {
                if ($cuttingOrderRecord->cuttingOrderRecordDetail != null) {
                    $cuttingOrderRecord->id_status_layer = 4;
                }
            } else {
                $cuttingOrderRecord->id_status_layer = $cuttingOrderRecord->id_status_layer;
            }

            $cuttingOrderRecordDetail->save();
            $cuttingOrderRecord->save();
            $data = CuttingOrderRecord::where('cutting_order_records.id', $cuttingOrderRecord->id)->with('statusLayer', 'cuttingOrderRecordDetail.user')
                ->first();
            $data = collect(
                [
                    'cutting_order_record' => $data
                ]
            );
            return $this->onSuccess($data, 'Fabric roll berhasil di update.');
        } catch (\Exception $e) {
            return $this->onError(500, $e->getMessage());
        }
    }
    
    public function deleteFabricRoll(Request $request, $id)
    {
        try {
            $cuttingOrderRecordDetail = CuttingOrderRecordDetail::with('cuttingOrderRecord.statusCut')->where('id', $id)->first();
            if ($cuttingOrderRecordDetail->cuttingOrderRecord->statusCut->id == 2) {
                return $this->onSuccess(collect(['cutting_order_record_detail' => null]), 'Tidak bisa menghapus fabric roll, cutting order record sudah di potong.');
            }
            $cuttingOrderRecordDetail->delete();
            $cuttingOrderRecord = $cuttingOrderRecordDetail->cuttingOrderRecord;
            $sum_layer = 0;
            foreach ($cuttingOrderRecord->cuttingOrderRecordDetail as $detail) {
                $sum_layer += $detail->layer;
            }
            
            if ($sum_layer == $cuttingOrderRecord->layingPlanningDetail->layer_qty) {
                $status = StatusLayer::where('name', 'completed')->first();
                if ($status == null) return $this->onError(404, 'Status Layer Cut not found.');
                $cuttingOrderRecord->id_status_layer = $status->id;
                $cuttingOrderRecord->layer = Carbon::now();
            } else if ($sum_layer > $cuttingOrderRecord->layingPlanningDetail->layer_qty) {
                return $this->onSuccess(null, 'Layer Cut tidak boleh lebih dari Layer Qty.');
                $status = StatusLayer::where('name', 'over layer')->first();
                if ($status == null) return $this->onError(404, 'Status Layer Cut not found.');
                $cuttingOrderRecord->id_status_layer = $status->id;
            } else {
                $status = StatusLayer::where('name', 'not completed')->first();
                if ($status == null) return $this->onError(404, 'Status Layer Cut not found.');
                $cuttingOrderRecord->id_status_layer = $status->id;
            }

            if ($cuttingOrderRecord->id_status_layer == 1 && $cuttingOrderRecord->id_status_cut == 1) {
                if ($cuttingOrderRecord->cuttingOrderRecordDetail != null) {
                    $cuttingOrderRecord->id_status_layer = 4;
                }
            } else {
                $cuttingOrderRecord->id_status_layer = $cuttingOrderRecord->id_status_layer;
            }

            $cuttingOrderRecord->save();
            return $this->onSuccess(collect(['cutting_order_record_detail' => $cuttingOrderRecordDetail]), 'Fabric roll berhasil dihapus.');
        } catch (\Exception $e) {
            return $this->onError(500, $e->getMessage());
        }
    }

    public function getCuttingOrderRecordByGlId($id)
    {
        $data = CuttingOrderRecord::whereHas('layingPlanningDetail', function ($query) use ($id) {
            $query->whereHas('layingPlanning', function ($query) use ($id) {
                $query->whereHas('gl', function ($query) use ($id) {
                    $query->where('id', $id);
                });
            });
        })->get();
        $data = collect(
            [
                'cutting_order_record' => $data
            ]
        );
        return $this->onSuccess($data, 'Cutting Order Record retrieved successfully.');
    }

    public function getLayingPlanningDetailByCuttingOrderRecordId($id)
    {
        $data = CuttingOrderRecord::with('layingPlanningDetail', 'layingPlanningDetail.layingPlanning', 'layingPlanningDetail.layingPlanning.gl')->where('id', $id)->get();
        $data = collect(
            [
                'cutting_order_record' => $data
            ]
        );
        return $this->onSuccess($data, 'Cutting Order Record retrieved successfully.');
    }

    public function postStatusCut(Request $request)
    {
        $input = $request->all();
        $cuttingOrderRecord = CuttingOrderRecord::where('serial_number', $input['serial_number'])->first();
        $statusCut = StatusCut::where('name', $input['name'])->first();
        if ($statusCut == null) return $this->onError(404, 'Status Cut not found.'); // not relation
        $cuttingOrderRecord->id_status_cut = $statusCut->id;
        if ($cuttingOrderRecord->id_status_cut == 2) {
            $cuttingOrderRecord->cut = Carbon::now();
        }
        $cuttingOrderRecord->save();
        $data = CuttingOrderRecord::where('cutting_order_records.id', $cuttingOrderRecord->id)->with('statusCut')
            ->first();
        $data = collect(
            [
                'cutting_order_record' => $data
            ]
        );
        return $this->onSuccess($data, 'Cutting Order Record updated successfully.');
    }
    
    public function destroy($id)
    {
        $cuttingOrderRecordDetail = CuttingOrderRecordDetail::find($id);
        $cuttingOrderRecordDetail->delete();
        return $this->onSuccess($cuttingOrderRecordDetail, 'Cutting Order Record Detail deleted successfully.');
    }
}