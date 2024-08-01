<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BundleStockTransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        "serial_number",
        "transaction_type",
        "location_id",
    ];

    public function bundleLocation()
    {
        return $this->belongsTo(BundleLocation::class, 'location_id', 'id');
    }

    public function bundleStockTransaction()
    {
        return $this->hasMany(BundleStockTransaction::class, 'bundle_transaction_detail_id', 'id');
    }

}
