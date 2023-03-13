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
}
