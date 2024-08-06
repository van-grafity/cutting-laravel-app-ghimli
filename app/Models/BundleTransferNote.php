<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class BundleTransferNote extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'serial_number',
        'location_from_id',
        'location_to_id',
        'issued_by',
        'authorized_by',
        'received_by',
    ];

    public function bundleLocationTo()
    {
        return $this->belongsTo(BundleLocation::class, 'location_to_id', 'id');
    }

}
