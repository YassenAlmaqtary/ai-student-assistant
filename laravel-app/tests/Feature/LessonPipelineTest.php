<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LessonPipelineTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_lesson_and_pipeline_progress(): void
    {
        $user = User::factory()->create();
    $login = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
    $token = $login->json('data.token');

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/lessons', [
                'title' => 'My Lesson',
                'path' => 'uploads/test.mp4',
                'type' => 'video',
            ]);
        $response->assertStatus(201)->assertJson(['success' => true]);

        $lessonId = $response->json('data.lesson.id');
        $this->assertNotNull($lessonId);

        // Poll show endpoint
        $show = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/lessons/' . $lessonId);
        $show->assertStatus(200)->assertJsonStructure(['data' => ['lesson' => ['status','progress']]]);
    }
}
