<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayingPlanning extends Model
{
    use HasFactory;

    public function layingPlanningSizes()
    {
        return $this->hasMany(LayingPlanningSize::class, 'laying_planning_id', 'id');
    }

}