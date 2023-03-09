<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuttingOrderRecordDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'cutting_order_record_id',
        'fabric_roll',
        'fabric_batch',
        'color_id',
        'yardage',
        'weight',
        'layer',
        'joint',
        'balance_end',
        'remakrs',
    ];

    public function cuttingOrderRecord()
    {
        return $this->belongsTo(CuttingOrderRecord::class, 'cutting_order_record_id', 'id');
    }
}
