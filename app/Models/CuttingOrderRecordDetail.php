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
        'remarks',
        'operator',
        // 'user_id',
    ];

    public function cuttingOrderRecord()
    {
        return $this->belongsTo(CuttingOrderRecord::class, 'cutting_order_record_id', 'id');
    }
    
    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id', 'id');
    }

    public function cuttingTicket()
    {
        return $this->hasMany(CuttingTicket::class, 'cutting_order_record_detail_id', 'id');
    }

    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'user_id', 'id');
    // }
}
