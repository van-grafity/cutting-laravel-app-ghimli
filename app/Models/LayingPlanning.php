<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayingPlanning extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial_number',
        'gl_id',
        'style_id',
        'buyer_id',
        'color_id',
        'order_qty',
        'delivery_date',
        'plan_date',
        'fabric_po',
        'fabric_cons_id',
        'fabric_type_id',
        'fabric_cons_qty',
        'fabric_cons_desc',
        'remark',
        'laying_planning_type_id',
        'parent_laying_planning_id',
        'created_by',
        'status_print',
    ];

    public function layingPlanningSize()
    {
        return $this->hasMany(LayingPlanningSize::class, 'laying_planning_id', 'id');
    }

    public function gl()
    {
        return $this->belongsTo(Gl::class, 'gl_id', 'id');
    }

    public function style()
    {
        return $this->belongsTo(Style::class, 'style_id', 'id');
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class, 'buyer_id', 'id');
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id', 'id');
    }

    public function fabricType()
    {
        return $this->belongsTo(FabricType::class, 'fabric_type_id', 'id');
    }

    public function fabricCons()
    {
        return $this->belongsTo(FabricCons::class, 'fabric_cons_id', 'id');
    }

    public function layingPlanningDetail(){
        return $this->hasMany(LayingPlanningDetail::class, 'laying_planning_id', 'id');
    }

    public function layingPlanningType()
    {
        return $this->belongsTo(LayingPlanningType::class, 'laying_planning_type_id', 'id');
    }

    public function parentLayingPlanning()
    {
        return $this->belongsTo(LayingPlanning::class, 'parent_laying_planning_id', 'id');
    }

    public function childLayingPlannings()
    {
        return $this->hasMany(LayingPlanning::class, 'parent_laying_planning_id', 'id');
    }

}