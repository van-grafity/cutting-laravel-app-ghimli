<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buyer extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'name',
        'address',
        'shipment_address',
        'code',
    ];

    public function gls()
    {
        return $this->hasMany(Gl::class);
    }
}
