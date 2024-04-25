<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuttingTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'serial_number',
        'size_id',
        'layer',
        'cutting_order_record_id',
        'cutting_order_record_detail_id',
        'table_number',
        'fabric_roll',
    ];

    public function cuttingOrderRecord()
    {
        return $this->belongsTo(CuttingOrderRecord::class, 'cutting_order_record_id', 'id');
    }
    public function cuttingOrderRecordDetail()
    {
        return $this->belongsTo(CuttingOrderRecordDetail::class, 'cutting_order_record_detail_id', 'id');
    }

    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id', 'id');
    }
}