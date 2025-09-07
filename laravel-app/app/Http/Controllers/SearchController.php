<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chunk;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $data = $request->validate([
            'query' => 'required|string|min:2',
            'limit' => 'sometimes|integer|min:1|max:20',
            'lesson_id' => 'sometimes|exists:lessons,id'
        ]);
        $limit = $data['limit'] ?? 5;
        $q = $data['query'];

        // Placeholder: البحث داخل chunks محلياً قبل دمج Qdrant
        $chunksQuery = Chunk::query()->with('lesson')
            ->where('text', 'like', "%{$q}%");
        if (!empty($data['lesson_id'])) {
            $chunksQuery->where('lesson_id', $data['lesson_id']);
        }
        $chunks = $chunksQuery->limit($limit)->get();
        $results = $chunks->map(fn($c) => [
            'chunk_id' => $c->id,
            'lesson_id' => $c->lesson_id,
            'lesson_title' => $c->lesson->title ?? null,
            'preview' => mb_substr($c->text, 0, 120) . (mb_strlen($c->text) > 120 ? '...' : ''),
        ]);
    return $this->ok(['results' => $results]);
    }
}
