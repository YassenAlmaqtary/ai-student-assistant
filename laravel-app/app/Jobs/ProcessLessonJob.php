<?php

namespace App\Jobs;

use App\Models\Lesson;
use App\Models\Transcript;
use App\Models\Chunk;
use App\Models\Summary;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class ProcessLessonJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $lessonId) {}

    public function handle(): void
    {
        $lesson = Lesson::find($this->lessonId);
        if (!$lesson) return; // درس محذوف

        if ($lesson->status === 'ready') return; // جاهز بالفعل

        try {
            $lesson->update(['status' => 'processing', 'processing_started_at' => now(), 'progress' => 5, 'failure_reason' => null]);

            // 1. Transcript
            $transcript = $lesson->transcripts()->first();
            if (!$transcript) {
                $fakeText = "نص مبدئي للدرس: {$lesson->title}. سيتم استبداله لاحقاً بنص حقيقي مستخرج.";
                $transcript = Transcript::create([
                    'lesson_id' => $lesson->id,
                    'text' => $fakeText,
                ]);
            }
            $lesson->update(['progress' => 30]);

            // 2. Chunks
            $this->chunkTranscript($lesson, $transcript->text);
            $lesson->update(['progress' => 65]);

            // 3. Summary
            if ($lesson->summaries()->count() === 0) {
                $words = preg_split('/\s+/u', trim($transcript->text));
                $summaryText = implode(' ', array_slice($words, 0, 30));
                Summary::create([
                    'lesson_id' => $lesson->id,
                    'style' => 'brief',
                    'text' => $summaryText,
                ]);
            }
            $lesson->update(['progress' => 85]);

            // 4. Ready
            $lesson->update(['status' => 'ready', 'progress' => 100, 'processing_finished_at' => now()]);
        } catch (\Throwable $e) {
            $lesson->update([
                'status' => 'failed',
                'progress' => 0,
                'failure_reason' => substr($e->getMessage(), 0, 1000),
            ]);
            throw $e; // يسمح بإعادة المحاولة بواسطة نظام الطوابير
        }
    }

    protected function chunkTranscript(Lesson $lesson, string $text): void
    {
        if ($lesson->chunks()->exists()) {
            return; // chunks موجودة مسبقاً
        }
        // تقسيم مبسط للجمل ثم تجميع بحد أقصى ~800 حرف لكل chunk
        $sentences = preg_split('/(?<=[.!؟\n])\s+/u', $text) ?: [$text];
        $current = '';
        $order = 1;
        foreach ($sentences as $s) {
            if (Str::length($current . ' ' . $s) > 800 && $current !== '') {
                $this->storeChunk($lesson, $current, $order++);
                $current = '';
            }
            $current = trim($current . ' ' . $s);
        }
        if ($current !== '') {
            $this->storeChunk($lesson, $current, $order++);
        }
    }

    protected function storeChunk(Lesson $lesson, string $text, int $order): void
    {
        // embedding مبدئي (hash) لاحقاً نستبدلها بمصفوفة أرقام حقيقية
        $hash = substr(sha1($text), 0, 32);
        Chunk::create([
            'lesson_id' => $lesson->id,
            'text' => $text,
            'embedding' => $hash,
            'order' => $order,
        ]);
    }
}
