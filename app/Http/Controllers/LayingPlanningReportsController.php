<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LayingPlanning;
use App\Models\LayingPlanningDetail;

use Carbon\Carbon;
use PDF;

class LayingPlanningReportsController extends Controller
{
    public function markerRequirement($id)
    {
        $layingPlanning = LayingPlanning::with('layingPlanningDetail')->find($id);
        $layingPlanningDetail = $layingPlanning->layingPlanningDetail;
        $filteredColumn = $layingPlanningDetail->map(function($data) {
            return [
                'marker_code' => $data->marker_code,
                'marker_length' => $data->marker_length,
                'marker_yard' => $data->marker_yard,
                'marker_inch' => $data->marker_inch,
                'size_ratio' => $this->getSizeRatio($data),
            ];
        });

        $uniqueMarker = $filteredColumn->groupBy('marker_code')->map(function ($grouped_by_marker_code) {
            $uniqueMarkerCode = $grouped_by_marker_code->first();
            $uniqueMarkerCode['marker_qty'] = $grouped_by_marker_code->count();
            $uniqueMarkerCode['total_length'] = $grouped_by_marker_code->sum('marker_length');

            return (object) $uniqueMarkerCode;
        })->values();

        $totalAllMarker = (object) [
            'total_all_qty' => $uniqueMarker->sum('marker_qty'),
            'total_all_length' => $uniqueMarker->sum('total_length'),
        ];
        
        $filename = 'Marker Requirement Report #' . $layingPlanning->gl->gl_number .' ( ' . $layingPlanning->color->color .' )';
        $data = [
            'layingPlanning' => $layingPlanning,
            'uniqueMarker' => $uniqueMarker,
            'totalAllMarker' => $totalAllMarker,
            'printedDate' => Carbon::now()->format('d F Y'),
            'filename' => $filename,
        ];

        $pdf = PDF::loadView('page.laying-planning-report.marker-requirement', $data)->setPaper('a4', 'landscape');
        return $pdf->stream($filename .'.pdf');
    }

    private function getSizeRatio(LayingPlanningDetail $layingPlanningDetail)
    {
        $layingPlanningDetailSize = $layingPlanningDetail->layingPlanningDetailSize;
        $filteredColumn = $layingPlanningDetailSize->mapWithKeys(function($data){
            return [
                $data->size_id => $data->ratio_per_size
            ];
        });
        return $filteredColumn->toArray();
    }
}
