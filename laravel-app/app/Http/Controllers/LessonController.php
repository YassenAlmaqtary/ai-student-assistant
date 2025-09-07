<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;
use App\Models\Transcript;
use App\Models\Summary;
use App\Models\Chunk;
use App\Jobs\ProcessLessonJob;

class LessonController extends Controller
{
    public function index(Request $request)
    {
        $query = Lesson::query()->where('user_id', $request->user()->id);

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }
        if ($search = $request->get('q')) {
            $query->where('title', 'like', "%{$search}%");
        }
        $query->orderByDesc('id');
        $lessons = $query->paginate(min(100, (int)$request->get('per_page', 15)));
        return $this->ok([
            'items' => $lessons->items(),
            'meta' => [
                'current_page' => $lessons->currentPage(),
                'last_page' => $lessons->lastPage(),
                'per_page' => $lessons->perPage(),
                'total' => $lessons->total(),
            ],
        ]);
    }

    public function show(Request $request, Lesson $lesson)
    {
        $this->authorizeLesson($request, $lesson);
    return $this->ok(['lesson' => $lesson]);
    }

    public function transcripts(Request $request, Lesson $lesson)
    {
        $this->authorizeLesson($request, $lesson);
    return $this->ok(['transcripts' => $lesson->transcripts()->latest('id')->get()]);
    }

    public function summaries(Request $request, Lesson $lesson)
    {
        $this->authorizeLesson($request, $lesson);
    return $this->ok(['summaries' => $lesson->summaries()->latest('id')->get()]);
    }

    public function chunks(Request $request, Lesson $lesson)
    {
        $this->authorizeLesson($request, $lesson);
    return $this->ok(['chunks' => $lesson->chunks()->orderBy('order')->get(['id','order','text'])]);
    }

    protected function authorizeLesson(Request $request, Lesson $lesson): void
    {
        if ($lesson->user_id !== $request->user()->id) {
            abort(403, 'غير مصرح');
        }
    }

    public function getUploadUrl(Request $request)
    {
        $request->validate([
            'filename' => 'required',
            'type' => 'required|in:video,audio,pdf,image,doc',
        ]);
        // هنا من المفترض توليد signed URL من MinIO
        $path = 'uploads/' . uniqid() . '_' . $request->filename;
        $upload_url = 'https://minio.local/' . $path; // استبدلها بتوليد حقيقي لاحقاً
    return $this->ok(['upload_url' => $upload_url, 'path' => $path]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'path' => 'required',
            'type' => 'required|in:video,audio,pdf,image,doc',
        ]);
        $lesson = Lesson::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'type' => $request->type,
            's3_path' => $request->path,
            'status' => 'pending',
        ]);
        ProcessLessonJob::dispatch($lesson->id);
    return $this->ok(['lesson' => $lesson], 201);
    }

    public function reprocess(Request $request, Lesson $lesson)
    {
        $this->authorizeLesson($request, $lesson);
        // حذف البيانات القديمة (اختياري) ثم إعادة الإطلاق
        $lesson->chunks()->delete();
        $lesson->summaries()->delete();
        // لا نحذف transcript لو جاء من webhook
        $lesson->update(['status' => 'pending']);
        ProcessLessonJob::dispatch($lesson->id);
    return $this->ok(['message' => 'أعيدت المعالجة', 'lesson' => $lesson]);
    }
}
