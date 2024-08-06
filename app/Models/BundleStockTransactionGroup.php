<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class BundleStockTransactionGroup extends Model
{
    use HasFactory;
    use SoftDeletes;

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
        return $this->hasMany(BundleStockTransaction::class, 'transaction_group_id', 'id');
    }
}
