<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    use HasFactory;

    protected $fillable = [
        'machine_type_id',
        'brand_id',
        'model',
        'serial_number'
    ];

    public function machine_type()
    {
        return $this->belongsTo(MachineType::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
