<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusLayer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function cuttingOrderRecord()
    {
        return $this->hasMany(CuttingOrderRecord::class);
    }
}
