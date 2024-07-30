<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BundleStockTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'transaction_type',
        'location_id',
    ];

    public function cuttingTicket()
    {
        return $this->belongsTo(CuttingTicket::class, 'ticket_id', 'id');
    }

    public function bundleStockTransactionDetail()
    {
        return $this->belongsTo(BundleStockTransactionDetail::class, 'bundle_transaction_detail_id', 'id');
    }
}
