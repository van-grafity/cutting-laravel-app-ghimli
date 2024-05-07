<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UserRecords;

class CuttingGroup extends Model
{
    use HasFactory, SoftDeletes, UserRecords;

    protected $fillable = [
        'group',
        'description',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'cutting_group_users', 'cutting_group_id', 'user_id');
    }

}
