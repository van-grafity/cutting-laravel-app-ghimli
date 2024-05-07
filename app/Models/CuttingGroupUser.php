<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuttingGroupUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cutting_group_id',
    ];
}
