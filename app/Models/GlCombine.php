<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlCombine extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_gl',
        'name',
        'description',
    ];

    public function gl()
    {
        return $this->belongsTo(Gl::class, 'id_gl', 'id');
    }
    
    public function layingPlanningSizeGlCombine()
    {
        return $this->hasMany(LayingPlanningSizeGlCombine::class, 'id_gl_combine', 'id');
    }
}