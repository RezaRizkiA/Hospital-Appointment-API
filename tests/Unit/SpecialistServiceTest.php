<?php

namespace Tests\Unit;

use App\Services\SpecialistService;
use App\Repositories\SpecialistRepository;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase; // <— penting: pakai base TestCase Laravel
use stdClass;

class SpecialistServiceTest extends TestCase
{
    use WithFaker;

    public function test_create_uploads_photo_and_persists_path()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('avatar.jpg', 600, 600);

        $repo = Mockery::mock(SpecialistRepository::class);
        $repo->shouldReceive('create')
            ->once()
            ->with(\Mockery::on(function ($data) {
                return isset($data['photo'])
                    && is_string($data['photo'])
                    && str_starts_with($data['photo'], 'specialists/');
            }))
            ->andReturnUsing(fn($data) => (object)$data);

        $service = new SpecialistService($repo);

        $payload = ['name' => 'Dr. A', 'photo' => $file];
        $created = $service->create($payload);

        Storage::disk('public')->assertExists($created->photo);
    }

    public function test_update_replaces_photo_and_deletes_old_file()
    {
        Storage::fake('public');

        $oldPath = 'specialists/old.jpg';
        Storage::disk('public')->put($oldPath, 'dummy');

        $old = new stdClass();
        $old->id = 10;
        $old->photo = $oldPath;

        $newFile = UploadedFile::fake()->image('new.jpg');

        $repo = Mockery::mock(SpecialistRepository::class);
        $repo->shouldReceive('getById')->once()->with(10, ['*'])->andReturn($old);
        $repo->shouldReceive('update')
            ->once()
            ->with(10, \Mockery::on(function ($data) {
                return isset($data['photo'])
                    && is_string($data['photo'])
                    && str_starts_with($data['photo'], 'specialists/');
            }))
            ->andReturnUsing(fn($id, $data) => (object) $data);

        $service = new SpecialistService($repo);

        $updated = $service->update(10, ['photo' => $newFile, 'name' => 'Dr. B']);

        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($updated->photo);
    }

    public function test_delete_removes_photo_then_calls_repository_delete()
    {
        Storage::fake('public');

        $oldPath = 'specialists/keepme.jpg';
        Storage::disk('public')->put($oldPath, 'dummy');

        $old = new stdClass();
        $old->id = 77;
        $old->photo = $oldPath;

        $repo = Mockery::mock(SpecialistRepository::class);
        $repo->shouldReceive('getById')->once()->with(77, ['*'])->andReturn($old);
        $repo->shouldReceive('delete')->once()->with(77)->andReturnNull();

        $service = new SpecialistService($repo);

        $service->delete(77);

        Storage::disk('public')->assertMissing($oldPath);
    }

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
}
