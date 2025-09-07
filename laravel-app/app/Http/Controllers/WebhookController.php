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
        Lesson::where('id', $data['lesson_id'])->update(['status' => 'ready']);
        return response()->json(['ok' => true]);
    }

    public function lesson(Request $request)
    {
        // لمعالجة أنواع أخرى (تلخيص، chunks...)
        return response()->json(['ok' => true]);
    }
}
