<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricRequisition extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial_number',
        'laying_planning_detail_id',
        'is_issue',
    ];

    public function layingPlanningDetail()
    {
        return $this->belongsTo(LayingPlanningDetail::class, 'laying_planning_detail_id', 'id');
    }

    public function fabricIssues()
    {
        return $this->hasMany(FabricIssue::class, 'fabric_request_id', 'id');
    }
    
}
