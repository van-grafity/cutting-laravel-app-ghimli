<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class BundleStockTransaction extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'ticket_id',
        'transaction_type',
        'location_id',
    ];
    public function cuttingTicket()
    {
        return $this->belongsTo(CuttingTicket::class, 'ticket_id', 'id');
    }

    public function bundleStockTransactionGroup()
    {
        return $this->belongsTo(BundleStockTransactionGroup::class, 'transaction_group_id', 'id');
    }
}
