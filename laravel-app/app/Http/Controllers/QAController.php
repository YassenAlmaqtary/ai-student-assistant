<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;
use App\Models\QaSession;
use App\Models\Chunk;

class QAController extends Controller
{
    public function sessions(Request $request, Lesson $lesson)
    {
        if ($lesson->user_id !== $request->user()->id) {
            return $this->fail('غير مصرح', 403);
        }
        $items = QaSession::where('lesson_id', $lesson->id)
            ->orderByDesc('id')
            ->limit(50)
            ->get()
            ->map(fn($s) => [
                'id' => $s->id,
                'question' => $s->question,
                'answer' => $s->answer,
                'sources' => json_decode($s->sources, true),
                'created_at' => $s->created_at?->toISOString(),
            ]);
        return $this->ok(['sessions' => $items]);
    }
    public function answer(Request $request)
    {
        $data = $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'question' => 'required|string|min:3'
        ]);
        $lesson = Lesson::findOrFail($data['lesson_id']);
        if ($lesson->user_id !== $request->user()->id) {
            return $this->fail('غير مصرح', 403);
        }
        // استرجاع أفضل 3 chunks (بحث نصي بسيط مؤقت)
        $chunks = Chunk::where('lesson_id', $lesson->id)
            ->where('text', 'like', '%' . $data['question'] . '%')
            ->orderBy('order')
            ->limit(3)
            ->get();
        if ($chunks->isEmpty()) {
            $chunks = Chunk::where('lesson_id', $lesson->id)->orderBy('order')->limit(3)->get();
        }
        $context = $chunks->pluck('text')->implode("\n---\n");
        // إجابة مبدئية (سيتم لاحقاً استبدالها باستدعاء LLM)
        $answer = 'إجابة مبدئية: سيتم تشغيل نموذج الذكاء لاحقاً. سياق مقتطف من الدرس.';
        $sourcesArray = $chunks->map(fn($c) => [
            'chunk_id' => $c->id,
            'preview' => mb_substr($c->text, 0, 100) . (mb_strlen($c->text) > 100 ? '...' : ''),
        ])->values()->all();
        $qa = QaSession::create([
            'lesson_id' => $lesson->id,
            'question' => $data['question'],
            'answer' => $answer,
            'sources' => json_encode($sourcesArray, JSON_UNESCAPED_UNICODE),
        ]);
        return $this->ok([
            'answer' => $qa->answer,
            'sources' => json_decode($qa->sources, true),
            'session' => [
                'id' => $qa->id,
                'question' => $qa->question,
                'answer' => $qa->answer,
                'sources' => json_decode($qa->sources, true),
                'created_at' => $qa->created_at?->toISOString(),
            ],
        ]);
    }
}
