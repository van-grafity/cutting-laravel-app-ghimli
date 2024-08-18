<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BundleCut extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'ticket_id',
        'status_id',
        'remarks'
    ];

    public function cuttingTicket()
    {
        return $this->belongsTo(CuttingTicket::class, 'ticket_id', 'id');
    }

    public function bundleStatus()
    {
        return $this->belongsTo(BundleStatus::class, 'status_id', 'id');
    }
}