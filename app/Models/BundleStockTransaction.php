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

    public function bundleLocation()
    {
        return $this->belongsTo(BundleLocation::class, 'location_id', 'id');
    }
}