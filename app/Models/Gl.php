<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gl extends Model
{
    use HasFactory;

    protected $fillable = [
        'gl_number',
        'season',
        'size_order',
        'buyer_id',
        'code',
    ];

    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }

    public function style()
    {
        return $this->hasMany(Style::class,'gl_id','id');
    }

    public function layingPlanning(){
        return $this->hasMany(LayingPlanning::class, 'gl_id', 'id');
    }

    public function glCombine()
    {
        return $this->hasMany(GlCombine::class, 'id_gl', 'id');
    }
}