<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayingPlanningDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_laying_sheet',
        'table_number',
        'laying_planning_id',
        'layer_qty',
        'marker_code',
        'marker_yard',
        'marker_inch',
        'marker_length',
        'total_length',
        'total_all_size',
    ];

    public function layingPlanningDetailSize()
    {
        return $this->hasMany(LayingPlanningDetailSize::class, 'laying_planning_detail_id', 'id');
    }

    public function cuttingOrderRecord()
    {
        return $this->hasOne(CuttingOrderRecord::class, 'laying_planning_detail_id', 'id');
    }



}