<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UserRecords;

class BundleStockTransactionGroup extends Model
{
    use HasFactory;
    use SoftDeletes, UserRecords;

    protected $fillable = [
        "serial_number",
        "transaction_type",
        "location_id",
        "created_by",
        "updated_by",
        "deleted_by",
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
