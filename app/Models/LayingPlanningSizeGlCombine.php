<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayingPlanningSizeGlCombine extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_laying_planning_size',
        'id_gl_combine',
    ];

    public function layingPlanningSize()
    {
        return $this->belongsTo(LayingPlanningSize::class, 'id_laying_planning_size', 'id');
    }
    
    public function glCombine()
    {
        return $this->belongsTo(GlCombine::class, 'id_gl_combine', 'id');
    }
}