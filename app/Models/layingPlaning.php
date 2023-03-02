<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class layingPlaning extends Model
{
    use HasFactory;

    public function layingPlaningSizes()
    {
        return $this->hasMany(LayingPlaningSize::class, 'laying_planing_id', 'id');
    }

}