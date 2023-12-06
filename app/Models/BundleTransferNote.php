<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BundleTransferNote extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'serial_number',
        'location_id',
        'issued_by',
        'authorized_by',
        'received_by',
    ];

    public function bundleLocation()
    {
        return $this->belongsTo(BundleLocation::class, 'location_id', 'id');
    }

}
