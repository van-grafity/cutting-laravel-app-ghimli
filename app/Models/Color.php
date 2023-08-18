<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Color extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'color',
        'color_code',
    ];

    protected static $logAttributes = [
        'color',
        'color_code',
    ];

    protected static $logOnlyDirty = true;

    public function laying_planning()
    {
        return $this->hasMany(LayingPlanning::class, 'color_id', 'id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['color', 'color_code'])
            ->useLogName('color_log')
            ->logOnlyDirty();
    }
}