<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Summary extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('summary')
            ->logOnly(['lesson_id'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "تم {$eventName} ملخص");
    }
    protected $fillable = [
        'lesson_id',
        'style',
        'text',
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
