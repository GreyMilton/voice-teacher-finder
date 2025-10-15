<?php

use App\Filament\Resources\Teachers\Pages\ListTeachers;
use App\Models\Teacher;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('page can be loaded', function () {
    $teachers = Teacher::factory(random_int(1, 10))->create();

    livewire(ListTeachers::class)
        ->assertOk()
        ->assertCanSeeTableRecords($teachers);
});

test('admin can access page', function () {
    Teacher::factory(random_int(1, 10))->create();

    $admin = User::factory()->adminEmail()->create();

    $this->actingAs($admin)
        ->get(ListTeachers::getUrl())
        ->assertOk();
});

test('guest cannot access page and is redirected to login', function () {
    Teacher::factory(random_int(1, 10))->create();

    $this->assertGuest()
        ->get(ListTeachers::getUrl())
        ->assertRedirect(Filament::getLoginUrl());
});
