<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gl extends Model
{
    use HasFactory;

    protected $fillable = [
        'season',
        'size_order',
        'buyer_id',
        'code',
    ];

    public function buyer()
    {
        return $this->belongsTo(buyer::class);
    }
}
