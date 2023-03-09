<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayingPlanningDetailSize extends Model
{
    use HasFactory;

    protected $fillable = [
        'laying_planning_detail_id',
        'size_id',
        'ratio_per_size',
        'qty_per_size',
    ];

    public function size()
    {
        return $this->belongsTo(size::class, 'size_id', 'id');
    }
}
