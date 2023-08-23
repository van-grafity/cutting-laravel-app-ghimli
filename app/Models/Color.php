<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;
    protected $fillable = [
        'color',
        'color_code',
    ];

    public function layingPlanning(){
        return $this->hasMany(LayingPlanning::class, 'color_id', 'id');
    }
}