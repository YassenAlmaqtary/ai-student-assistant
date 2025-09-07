<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class QaSession extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('qa_session')
            ->logOnly(['lesson_id'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "تم {$eventName} جلسة سؤال وجواب");
    }
    protected $fillable = [
        'lesson_id',
        'question',
        'answer',
        'sources',
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
