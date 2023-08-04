<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayingPlanningSize extends Model
{
    use HasFactory;
    public $timestamps = false;
    
    protected $fillable = [
        'laying_planning_id',
        'size_id',
        'quantity',
    ];
    
    public function size()
    {
        return $this->belongsTo(size::class, 'size_id', 'id');
    }
    
    public function layingPlanning()
    {
        return $this->belongsTo(LayingPlanning::class, 'laying_planning_id', 'id');
    }

    public function glCombine()
    {
        return $this->hasMany(LayingPlanningSizeGlCombine::class, 'id_laying_planning_size', 'id');
    }
}