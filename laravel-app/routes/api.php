<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::get('/lessons', [App\Http\Controllers\LessonController::class, 'index']);
    Route::get('/lessons/{lesson}', [App\Http\Controllers\LessonController::class, 'show']);
    Route::get('/lessons/{lesson}/transcripts', [App\Http\Controllers\LessonController::class, 'transcripts']);
    Route::get('/lessons/{lesson}/summaries', [App\Http\Controllers\LessonController::class, 'summaries']);
    Route::get('/lessons/{lesson}/chunks', [App\Http\Controllers\LessonController::class, 'chunks']);
    Route::post('/lessons/{lesson}/reprocess', [App\Http\Controllers\LessonController::class, 'reprocess']);
    Route::post('/lessons/upload-url', [App\Http\Controllers\LessonController::class, 'getUploadUrl']);
    Route::post('/lessons/direct-upload', [App\Http\Controllers\LessonController::class, 'directUpload']);
    Route::post('/lessons', [App\Http\Controllers\LessonController::class, 'store']);
    Route::post('/search', [App\Http\Controllers\SearchController::class, 'search'])->middleware('throttle:30,1');
    Route::post('/qa', [App\Http\Controllers\QAController::class, 'answer'])->middleware('throttle:15,1');
    Route::get('/lessons/{lesson}/qa-sessions', [App\Http\Controllers\QAController::class, 'sessions']);
    Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout']);
    Route::post('/refresh', [App\Http\Controllers\AuthController::class, 'refresh']);
    Route::get('/me', [App\Http\Controllers\AuthController::class, 'me']);
});

Route::post('/hooks/processing/pdf', [App\Http\Controllers\WebhookController::class, 'pdf'])
    ->middleware('webhook.secret');
Route::post('/hooks/processing/lesson', [App\Http\Controllers\WebhookController::class, 'lesson'])
    ->middleware('webhook.secret');

// Auth
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->middleware('throttle:10,1');
