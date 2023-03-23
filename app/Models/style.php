<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Style extends Model
{
    use HasFactory;

    protected $fillable = [
        'style',
        'description',
        'gl_id',
    ];

    public function gl()
    {
        return $this->belongsTo(gl::class, 'gl_id', 'id');
    }
}
