<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CuttingOrderRecord extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'serial_number',
        'laying_planning_detail_id',
        'id_status_layer',
        'id_status_cut',
        'created_by',
        'status_print',
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
    
    public function statusLayer()
    {
        return $this->belongsTo(StatusLayer::class, 'id_status_layer', 'id');
    }
    
    public function statusCut()
    {
        return $this->belongsTo(StatusCut::class, 'id_status_cut', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function cuttingOrderRecordSticker()
    {
        return $this->hasMany(CuttingOrderRecordSticker::class, 'cutting_order_record_id', 'id');
    }
}
