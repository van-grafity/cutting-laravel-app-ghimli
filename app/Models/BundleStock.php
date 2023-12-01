<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BundleStock extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'laying_planning_id',
        'size_id',
        'current_qty',
    ];

    public function layingPlanning()
    {
        return $this->belongsTo(LayingPlanning::class, 'laying_planning_id', 'id');
    }

    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id', 'id');
    }
}