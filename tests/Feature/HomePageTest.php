<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('guests can visit the home page', function () {
    $response = $this->get(route('home'));
    $response->assertStatus(200);
});

test('authenticated users can visit the home page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('home'));
    $response->assertStatus(200);
});
