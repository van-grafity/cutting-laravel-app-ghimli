<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuttingOrderRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial_number',
        'laying_planning_detail_id',
    ];

    public function layingPlanningDetail()
    {
        return $this->belongsTo(LayingPlanningDetail::class, 'laying_planning_detail_id', 'id');
    }

    public function cuttingOrderRecordDetail()
    {
        return $this->hasMany(CuttingOrderRecordDetail::class, 'cutting_order_record_id', 'id');
    }

    public function cuttingTicket()
    {
        return $this->hasMany(CuttingTicket::class, 'cutting_order_record_id', 'id');
    }

    
}
