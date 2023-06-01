<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'group_name',
        'group_description',
    ];
    
    public function userGroups()
    {
        return $this->hasMany(UserGroups::class, 'group_id', 'id');
    }
}