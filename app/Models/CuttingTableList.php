<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuttingTableList extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_laying_sheet',
        'total_qty',
        'marker_code',
        'marker_length',
        'total_length',
        'layer_qty',
        'status_id',
    ];

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
