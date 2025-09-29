<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('guests can visit the faqs page', function () {
    $this->get(route('faqs'))
        ->assertStatus(200);
});

test('authenticated users can visit the faqs page', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('faqs'))
        ->assertStatus(200);
});
