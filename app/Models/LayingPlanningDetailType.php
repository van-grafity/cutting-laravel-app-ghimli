<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayingPlanningDetailType extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'description',
    ];

    public function layingPlnanningDetails()
    {
        return $this->hasMany(LayingPlanning::class,'laying_planning_type_id','id');
    }
}
