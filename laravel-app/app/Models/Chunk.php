<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class   Chunk extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('chunk')
            ->logOnly(['lesson_id'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "تم {$eventName} مقطع");
    }
    protected $fillable = [
        'lesson_id',
        'text',
        'embedding',
        'order',
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
