<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayingPlanning extends Model
{
    use HasFactory;

    protected $fillable = [
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
    ];

    public function layingPlanningSizes()
    {
        return $this->hasMany(LayingPlanningSize::class, 'laying_planning_id', 'id');
    }

    public function gl()
    {
        return $this->belongsTo(gl::class, 'gl_id', 'id');
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

}