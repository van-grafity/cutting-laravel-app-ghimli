<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionCategory extends Model
{
    use HasFactory;
    
    protected $table = 'permissions_categories';
    protected $fillable = [
        'name',
        'description',
    ];
}
