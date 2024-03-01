<?php

namespace App\Observers;

use App\Models\CuttingOrderRecord;

class CuttingOrderRecordObserver
{
    
    public $afterCommit = true;

    /**
     * Handle the CuttingOrderRecord "created" event.
     *
     * @param  \App\Models\CuttingOrderRecord  $cuttingOrderRecord
     * @return void
     */
    public function created(CuttingOrderRecord $cuttingOrderRecord)
    {
        $cuttingOrderRecord->created_by = Auth()->user()->id;
    }

    /**
     * Handle the CuttingOrderRecord "updated" event.
     *
     * @param  \App\Models\CuttingOrderRecord  $cuttingOrderRecord
     * @return void
     */
    public function updated(CuttingOrderRecord $cuttingOrderRecord)
    {
        $cuttingOrderRecord->updated_by = Auth()->user()->id;
    }

    /**
     * Handle the CuttingOrderRecord "deleted" event.
     *
     * @param  \App\Models\CuttingOrderRecord  $cuttingOrderRecord
     * @return void
     */
    public function deleted(CuttingOrderRecord $cuttingOrderRecord)
    {
        $cuttingOrderRecord->deleted_by = Auth()->user()->id;
        $cuttingOrderRecord->save();
    }

    /**
     * Handle the CuttingOrderRecord "restored" event.
     *
     * @param  \App\Models\CuttingOrderRecord  $cuttingOrderRecord
     * @return void
     */
    public function restored(CuttingOrderRecord $cuttingOrderRecord)
    {
        $cuttingOrderRecord->deleted_by = null;
        $cuttingOrderRecord->save();
    }

    /**
     * Handle the CuttingOrderRecord "force deleted" event.
     *
     * @param  \App\Models\CuttingOrderRecord  $cuttingOrderRecord
     * @return void
     */
    public function forceDeleted(CuttingOrderRecord $cuttingOrderRecord)
    {
        //
    }
}
