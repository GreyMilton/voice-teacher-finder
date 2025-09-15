<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('guests can visit the teacher index', function () {
    $response = $this->get(route('teacher.index'));
    $response->assertStatus(200);
});

test('authenticated users can visit the teacher index', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('teacher.index'));
    $response->assertStatus(200);
});
