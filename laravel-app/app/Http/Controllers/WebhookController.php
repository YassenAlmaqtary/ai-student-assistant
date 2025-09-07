<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;
use App\Models\Transcript;
use App\Models\Summary;

class WebhookController extends Controller
{
    public function pdf(Request $request)
    { 
        // تحقق من التوكن في middleware
        $data = $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'text' => 'required',
        ]);
        Transcript::create([
            'lesson_id' => $data['lesson_id'],
            'text' => $data['text'],
        ]);
        // تقسيم النص البسيط (مؤقت) إلى أجزاء طول كل منها ~800 حرف
        $chunks = [];
        $raw = preg_split('/\n+/', $data['text']);
        $buffer = '';
        foreach ($raw as $line) {
            $line = trim($line);
            if ($line === '') continue;
            if (mb_strlen($buffer . ' ' . $line) > 800) {
                $chunks[] = $buffer;
                $buffer = $line;
            } else {
                $buffer = $buffer ? $buffer . "\n" . $line : $line;
            }
        }
        if ($buffer) $chunks[] = $buffer;

        // تخزين مبدئي للـ chunks في جدول chunks إن وجد
        try {
            $lesson = Lesson::find($data['lesson_id']);
            foreach ($chunks as $i => $ch) {
                $lesson->chunks()->create(['order' => $i, 'text' => $ch]);
            }
        } catch (\Throwable $e) {
            // تجاهل بصمت مبدئياً
        }

        Lesson::where('id', $data['lesson_id'])->update(['status' => 'ready']);
        return response()->json(['ok' => true]);
    }

    public function lesson(Request $request)
    {
        // لمعالجة أنواع أخرى (تلخيص، chunks...)
        return response()->json(['ok' => true]);
    }
}
