<?php

namespace App\Observers;

use App\Models\CuttingOrderRecord;
use Illuminate\Support\Facades\Auth;

class CuttingOrderRecordObserver
{
    
    public $afterCommit = true;

    /**
     * Handle the CuttingOrderRecord "created" event.
     *
     * @param  \App\Models\CuttingOrderRecord  $cuttingOrderRecord
     * @return void
     */
    public function creating(CuttingOrderRecord $cuttingOrderRecord)
    {
        $cuttingOrderRecord->created_by = Auth::id();
    }

    /**
     * Handle the CuttingOrderRecord "updated" event.
     *
     * @param  \App\Models\CuttingOrderRecord  $cuttingOrderRecord
     * @return void
     */
    public function updating(CuttingOrderRecord $cuttingOrderRecord)
    {
        $cuttingOrderRecord->updated_by = Auth::id();
    }   

    /**
     * Handle the CuttingOrderRecord "deleted" event.
     *
     * @param  \App\Models\CuttingOrderRecord  $cuttingOrderRecord
     * @return void
     */
    public function deleted(CuttingOrderRecord $cuttingOrderRecord)
    {
        $cuttingOrderRecord->deleted_by = Auth::id();
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
