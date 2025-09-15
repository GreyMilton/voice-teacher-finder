<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('guests can visit the faqs page', function () {
    $response = $this->get(route('faqs'));
    $response->assertStatus(200);
});

test('authenticated users can visit the faqs page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('faqs'));
    $response->assertStatus(200);
});
