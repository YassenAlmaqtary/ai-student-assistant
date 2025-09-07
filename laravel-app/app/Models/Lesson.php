<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Lesson extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('lesson')
            ->logOnly(['title','status','type','course_id','user_id'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "تم {$eventName} درس");
    }
    protected $fillable = [
        'user_id',
        'course_id',
        'title',
        'type',
        's3_path',
        'status',
    'progress',
    'processing_started_at',
    'processing_finished_at',
    'failure_reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function transcripts()
    {
        return $this->hasMany(Transcript::class);
    }

    public function chunks()
    {
        return $this->hasMany(Chunk::class);
    }

    public function summaries()
    {
        return $this->hasMany(Summary::class);
    }

    public function qaSessions()
    {
        return $this->hasMany(QaSession::class);
    }
}
