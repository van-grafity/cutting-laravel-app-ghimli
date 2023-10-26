<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuttingOrderRecordSticker extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'cutting_order_record_id',
        'photo',
    ];

    public function cuttingOrderRecord()
    {
        return $this->belongsTo(CuttingOrderRecord::class, 'cutting_order_record_id', 'id');
    }
}