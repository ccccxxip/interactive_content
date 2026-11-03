<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FeedbackTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    public function test_home_page_is_accessible(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Главная страница');
    }

    public function test_feedback_page_displays_form(): void
    {
        $response = $this->get('/feedback');
        $response->assertStatus(200);
        $response->assertSee('Форма');
        $response->assertSee('name="name"', false);
        $response->assertSee('name="email"', false);
        $response->assertSee('name="topic"', false);
        $response->assertSee('name="message"', false);
    }

    public function test_validation_fails_when_fields_are_empty(): void
    {
        $response = $this->post('/feedback', []);
        $response->assertSessionHasErrors(['name', 'email', 'topic', 'message']);
    }

    public function test_form_submits_successfully_and_saves_json_file(): void
    {
        $payload = [
            'name'    => 'Иван Петров',
            'email'   => 'ivan@example.com',
            'topic'   => 'Тема сообщения',
            'message' => 'Тестовый текст сообщения.',
        ];

        $response = $this->post('/feedback', $payload);

        $response->assertRedirect('/feedback');
        $response->assertSessionHas('success');

        $files = Storage::disk('local')->files('submissions');
        $this->assertNotEmpty($files);

        $fileName = $files[0];
        $this->assertStringEndsWith('.json', $fileName);

        $content = Storage::disk('local')->get($fileName);
        $data = json_decode($content, true);

        $this->assertEquals('Иван Петров', $data['name']);
        $this->assertEquals('ivan@example.com', $data['email']);
        $this->assertEquals('Тема сообщения', $data['topic']);
        $this->assertEquals('Тестовый текст сообщения.', $data['message']);
        $this->assertArrayHasKey('created_at', $data);
    }

    public function test_submissions_page_displays_saved_records_in_table(): void
    {
        $testData = [
            'name'       => 'Анна Сидорова',
            'email'      => 'anna@example.com',
            'topic'      => 'Проверка таблицы',
            'message'    => 'Текст для проверки вывода в таблицу',
            'created_at' => '2026-11-03 12:00:00',
        ];

        Storage::disk('local')->put(
            'submissions/data_test_123.json',
            json_encode($testData)
        );

        $response = $this->get('/submissions');
        $response->assertStatus(200);
        $response->assertSee('data_test_123.json');
        $response->assertSee('Анна Сидорова');
        $response->assertSee('anna@example.com');
        $response->assertSee('Проверка таблицы');
    }
}
