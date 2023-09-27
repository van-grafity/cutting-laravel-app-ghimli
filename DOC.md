## User Manual

```
Fabric request  :
+- 3% dari faric request dept. cutting
```

## Referensi
```
public function printStatusCuttingOrderRecord(Request $request)
    {
        $date_start = $request->date_start;
        $date_end = $request->date_end;
        $gl_number = $request->gl_number;
        $status_layer = $request->status_layer;
        $status_cut = $request->status_cut;

        // get data from cutting order record
        $cuttingOrderRecord = CuttingOrderRecord::with(['statusLayer', 'statusCut', 'CuttingOrderRecordDetail', 'layingPlanningDetail', 'layingPlanningDetail.layingPlanning', 'layingPlanningDetail.layingPlanning.gl', 'layingPlanningDetail.layingPlanning.color', 'layingPlanningDetail.layingPlanning.style'])
            ->whereDate('updated_at', '>=', $date_start)
            ->whereDate('updated_at', '<=', $date_end)
            ->whereHas('layingPlanningDetail', function($query) use ($gl_number) {
                $query->whereHas('layingPlanning', function($query) use ($gl_number) {
                    if ($gl_number != null) {
                        $query->where('gl_id', $gl_number);
                    }
                });
            })
            ->whereHas('statusLayer', function($query) use ($status_layer) {
                if ($status_layer != null) {
                    $query->where('id', $status_layer);
                }
            })
            ->whereHas('statusCut', function($query) use ($status_cut) {
                if ($status_cut != null) {
                    $query->where('id', $status_cut);
                }
            })
            ->orderBy('serial_number', 'asc')
            ->get();
        $cuttingOrderRecord = $cuttingOrderRecord->sortBy(function($item) {
            return $item->layingPlanningDetail->layingPlanning->color->color;
        });
        $cuttingOrderRecord = $cuttingOrderRecord->sortBy(function($item) {
            return $item->layingPlanningDetail->layingPlanning->style->style;
        });

        // get data from  layinplanningdetail
        $layingPlanningDetail = LayingPlanningDetail::with(['layingPlanning', 'layingPlanning.gl', 'layingPlanning.color', 'layingPlanning.style', 'cuttingOrderRecord', 'cuttingOrderRecord.cuttingOrderRecordDetail', 'cuttingOrderRecord.statusLayer', 'cuttingOrderRecord.statusCut'])
            ->whereHas('layingPlanning', function($query) use ($gl_number) {
                $query->whereHas('gl', function($query) use ($gl_number) {
                    if ($gl_number != null) {
                        $query->where('id', $gl_number);
                    }
                });
            })
            ->whereHas('cuttingOrderRecord', function($query) use ($date_start, $date_end, $status_layer, $status_cut) {
                $query->whereDate('updated_at', '>=', $date_start)
                    ->whereDate('updated_at', '<=', $date_end)
                    ->whereHas('statusLayer', function($query) use ($status_layer) {
                        if ($status_layer != null) {
                            $query->where('id', $status_layer);
                        }
                    })
                    ->whereHas('statusCut', function($query) use ($status_cut) {
                        if ($status_cut != null) {
                            $query->where('id', $status_cut);
                        }
                    });
            })
            ->orderBy('id', 'asc')
            ->get();
        
            // get data from laying planning
            $layingPlanning = LayingPlanning::with(['gl', 'color', 'style', 'layingPlanningDetail', 'layingPlanningDetail.layingPlanningDetailSize', 'layingPlanningDetail.cuttingOrderRecord', 'layingPlanningDetail.fabricRequisition', 'layingPlanningDetail.cuttingOrderRecord.cuttingOrderRecordDetail', 'layingPlanningDetail.cuttingOrderRecord.statusLayer', 'layingPlanningDetail.cuttingOrderRecord.statusCut'])
                ->whereHas('gl', function($query) use ($gl_number) {
                    if ($gl_number != null) {
                        $query->where('id', $gl_number);
                    }
                })
                ->whereHas('layingPlanningDetail', function($query) use ($date_start, $date_end, $status_layer, $status_cut) {
                    $query->whereHas('cuttingOrderRecord', function($query) use ($date_start, $date_end, $status_layer, $status_cut) {
                        $query->whereDate('updated_at', '>=', $date_start)
                            ->whereDate('updated_at', '<=', $date_end)
                            ->whereHas('statusLayer', function($query) use ($status_layer) {
                                if ($status_layer != null) {
                                    $query->where('id', $status_layer);
                                }
                            })
                            ->whereHas('statusCut', function($query) use ($status_cut) {
                                if ($status_cut != null) {
                                    $query->where('id', $status_cut);
                                }
                            });
                    });
                })
                ->orderBy('id', 'asc')
                ->get();

                return $layingPlanning;
            
        
        $pdf = PDF::loadview('page.cutting-order.report-status', compact('data'))->setPaper('a4', 'landscape');
        return $pdf->stream('Report Status Cutting Order Record.pdf');
    }
```