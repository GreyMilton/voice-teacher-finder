<?php

use App\Filament\Resources\Teachers\Pages\ViewTeacher;
use App\Models\Teacher;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $teacher = Teacher::factory()->create();

    livewire(ViewTeacher::class, [
        'record' => $teacher->id,
    ])->assertOk();
});

test('admin can access page', function () {
    $admin = User::factory()->adminEmail()->create();
    $teacher = Teacher::factory()->create();

    $this->actingAs($admin)
        ->get(ViewTeacher::getUrl([
            'record' => $teacher->id,
        ]))
        ->assertOk();
});

test('guest cannot access page and is redirected to login', function () {
    $teacher = teacher::factory()->create();

    $this->assertGuest()
        ->get(ViewTeacher::getUrl([
            'record' => $teacher->id,
        ]))
        ->assertRedirect(Filament::getLoginUrl());
});
