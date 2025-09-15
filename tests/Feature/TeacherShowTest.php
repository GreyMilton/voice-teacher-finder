<?php

use App\Models\Teacher;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('guests can visit the teacher show', function () {
    $teacher = Teacher::factory()->create();
    $response = $this->get(route('teacher.show', ['teacher' => $teacher]));
    $response->assertStatus(200);
});

test('authenticated users can visit the teacher show', function () {
    $teacher = Teacher::factory()->create();
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('teacher.show', ['teacher' => $teacher]));
    $response->assertStatus(200);
});
