<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricIssue extends Model
{
    use HasFactory;

    protected $fillable = [
        'fabric_request_id',
        'roll_no',
        'weight',
    ];

    public function fabricRequest()
    {
        return $this->belongsTo(FabricRequisition::class, 'fabric_request_id');
    }
}
