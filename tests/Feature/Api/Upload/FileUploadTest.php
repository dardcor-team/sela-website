<?php

namespace Tests\Feature\Api\Upload;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\Traits\ActsAsAuthenticatedUser;

class FileUploadTest extends TestCase
{
    use ActsAsAuthenticatedUser;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_upload_avatar_requires_auth(): void
    {
        $this->postJson('/api/upload/avatar', [])
            ->assertUnauthorized();
    }

    public function test_upload_avatar_validates_input(): void
    {
        $this->actingAsUser();

        $this->postJson('/api/upload/avatar', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['file']);
    }

    public function test_upload_avatar_returns_success(): void
    {
        $this->actingAsUser();
        Storage::fake('public');
        $file = UploadedFile::fake()->image('avatar.jpg');

        $this->postJson('/api/upload/avatar', [
            'file' => $file
        ])
            ->assertOk();
    }

    public function test_upload_task_file_requires_auth(): void
    {
        $this->postJson('/api/upload/task-file', [])
            ->assertUnauthorized();
    }

    public function test_upload_task_file_validates_input(): void
    {
        $this->actingAsUser();

        $this->postJson('/api/upload/task-file', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['file']);
    }

    public function test_upload_task_file_returns_success(): void
    {
        $this->actingAsUser();
        $file = UploadedFile::fake()->create('document.pdf', 100);

        $this->postJson('/api/upload/task-file', [
            'file' => $file
        ])
            ->assertOk();
    }
}
