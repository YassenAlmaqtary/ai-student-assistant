<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Transcript extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('transcript')
            ->logOnly(['lesson_id'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "تم {$eventName} نص مفرغ");
    }
    protected $fillable = [
        'lesson_id',
        'text',
        'timestamps',
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
