<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cutting extends Model
{
    use HasFactory;
    protected $fillable = [
        'job_number',
        'style_number',
        'table_number',
        'next_bundling',
        'color',
        'size',
    ];
}
